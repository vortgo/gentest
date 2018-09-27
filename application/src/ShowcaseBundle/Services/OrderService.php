<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 23:02
 */

namespace ShowcaseBundle\Services;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use PaymentBundle\DTO\CardInfo;
use PaymentBundle\DTO\OrderInfo;
use PaymentBundle\DTO\UserInfo;
use PaymentBundle\Entity\ResponseEntities\Order;
use ShowcaseBundle\Entity\Card as UsersCard;
use PaymentBundle\Entity\ResponseEntities\Card;
use ShowcaseBundle\Exceptions\CreateOrderException;
use ShowcaseBundle\Exceptions\GetPayFormException;
use PaymentBundle\Exceptions\PaymentTokenFormException;
use PaymentBundle\Services\PaymentService;
use ShowcaseBundle\Entity\Item;
use ShowcaseBundle\Entity\OrderForm;
use ShowcaseBundle\Entity\OrderFormItem;
use ShowcaseBundle\Exceptions\NotFoundOrderException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Workflow\StateMachine;

class OrderService
{
    /** @var PaymentService */
    private $paymentService;
    /** @var Registry */
    private $doctrine;
    /** @var ContainerInterface */
    private $serviceContainer;

    public function __construct($paymentService, $doctrine, $serviceContainer)
    {
        $this->paymentService = $paymentService;
        $this->doctrine = $doctrine;
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * Crete order
     *
     * @param Item $item
     * @param int $quantity
     * @return OrderForm
     * @throws CreateOrderException
     */
    public function createOrder(Item $item, int $quantity = 1)
    {
        if ($item->getCount() < $quantity) {
            throw new CreateOrderException('Not enough count of item');
        }

        $order = new OrderForm();
        $order->setPrice($item->getPrice());
        $this->doctrine->getManager()->persist($order);
        $this->doctrine->getManager()->flush();

        $orderFormItem = new OrderFormItem();
        $orderFormItem->setPrice($item->getPrice())
            ->setQuantity($quantity)
            ->setItem($item)
            ->setOrderForm($order);

        $item->setCount($item->getCount() - $quantity)
            ->addOrderFormItem($orderFormItem);
        $this->doctrine->getManager()->persist($item);

        $order->addOrderFormItem($orderFormItem);
        $this->doctrine->getManager()->persist($order);

        $this->doctrine->getManager()->flush();

        return $order;
    }

    /**
     * Process payment data and update order
     *
     * @param array $data
     * @throws NotFoundOrderException
     * @throws \PaymentBundle\Exceptions\ApiGetDataException
     */
    public function updatePayData(array $data)
    {
        $callbackResponse = $this->paymentService->processCallback($data);
        $orderResponse = $callbackResponse->getOrder();
        $cardResponse = $callbackResponse->getCard();
        $paymentStatus = $callbackResponse->getPaymentStatus();
        $paymentOperation = $callbackResponse->getPaymentOperation();

        /** @var OrderForm $order */
        $order = $this->doctrine->getRepository(OrderForm::class)->find($orderResponse->getOrderId());
        if (!$order) {
            throw new NotFoundOrderException("Order with {$orderResponse->getOrderId()} not found");
        }

        /** @var StateMachine $stateMachine */
        $stateMachine = $this->serviceContainer->get('state_machine.order_form');
        if ($stateMachine->can($order, $this->getTransition($paymentStatus, $paymentOperation))) {
            if ($paymentOperation == 'refund' && $paymentStatus == OrderForm::STATUS_SUCCESS) {
                $order->setStatus(OrderForm::STATUS_REFUNDED);
            } else {
                $order->setStatus($paymentStatus);
            }

            if ($this->isNeedReturnItems($order)) {
                $this->returnItems($order);
            }
        }

        $this->doctrine->getManager()->persist($order);
        $this->doctrine->getManager()->flush();

        if ($cardResponse->getToken()) {
            $this->storeCard($cardResponse);
        }
    }

    /**
     * Check is need to return items
     *
     * @param OrderForm $order
     * @return bool
     */
    private function isNeedReturnItems(OrderForm $order)
    {
        return in_array($order->getStatus(), [OrderForm::STATUS_FAIL, OrderForm::STATUS_REFUNDED]);
    }

    /**
     * Get transitions for order
     *
     * @param $paymentStatus
     * @param $paymentOperation
     * @return string
     */
    private function getTransition($paymentStatus, $paymentOperation)
    {
        if ($paymentOperation == 'refund' && $paymentStatus == OrderForm::STATUS_SUCCESS) {
            return OrderForm::TRANSITION_REFUNDED;
        } else {
            return $paymentStatus;
        }
    }

    /**
     * Return items if order was failed or refunded
     *
     * @param OrderForm $order
     */
    public function returnItems(OrderForm $order)
    {
        $orderFormItems = $order->getOrderFormItems();
        /** @var OrderFormItem $orderItem */
        foreach ($orderFormItems as $orderItem) {
            $item = $orderItem->getItem();
            $item->setCount($item->getCount() + $orderItem->getQuantity());
            $this->doctrine->getManager()->persist($item);
        }
        $this->doctrine->getManager()->flush();
    }

    /**
     * Store user's card
     *
     * @param Card $card
     */
    private function storeCard(Card $card)
    {
        $usersCards = $this->doctrine->getRepository(UsersCard::class)->findBy([
            'country' => $card->getCountry(),
            'bank' => $card->getBank(),
            'brand' => $card->getBrand(),
            'number' => $card->getNumber(),
        ]);
        if (!$usersCards) {
            $usersCard = new UsersCard();
            $usersCard->setToken($card->getToken())
                ->setBank($card->getBank())
                ->setBrand($card->getBrand())
                ->setNumber($card->getNumber())
                ->setCountry($card->getCountry());

            $this->doctrine->getManager()->persist($usersCard);
            $this->doctrine->getManager()->flush();
        }
    }

    /**
     * Recurring
     *
     * @param UsersCard $card
     * @param OrderForm $order
     * @throws NotFoundOrderException
     * @throws \PaymentBundle\Exceptions\ApiGetDataException
     * @throws \PaymentBundle\Exceptions\PaymentRecurringException
     * @throws \ReflectionException
     */
    public function payRecurringOrder(UsersCard $card, OrderForm $order)
    {
        $userInfo = new UserInfo();
        $userInfo->setCustomerEmail('email@sdf.ru')
            ->setGeoCountry('GBR')
            ->setIpAddress('8.8.8.8');

        $orderInfo = new OrderInfo();
        $orderInfo->setAmount($order->getPrice()->getAmountInPennies())
            ->setCurrency($order->getPrice()->getCurrency())
            ->setOrderDescription('desc')
            ->setOrderId($order->getId());

        $cardInfo = new CardInfo();
        $cardInfo->setRecurringToken($card->getToken());

        $apiResponse = $this->paymentService->recurring($userInfo, $orderInfo, $cardInfo);

        /** @var StateMachine $stateMachine */
        $stateMachine = $this->serviceContainer->get('state_machine.order_form');
        $stateMachine->apply($order, 'pay');

        $this->updatePayData($apiResponse->getData());
    }

    /**
     * Refund order
     *
     * @param OrderForm $order
     * @throws NotFoundOrderException
     * @throws \PaymentBundle\Exceptions\ApiGetDataException
     * @throws \PaymentBundle\Exceptions\PaymentRefundingException
     * @throws \ReflectionException
     */
    public function refundOrder(OrderForm $order)
    {
        $orderInfo = new OrderInfo();
        $orderInfo->setAmount($order->getPrice()->getAmountInPennies() - 1)
            ->setCurrency($order->getPrice()->getCurrency())
            ->setOrderDescription('desc')
            ->setOrderId($order->getId());

        $apiResponse = $this->paymentService->refund($orderInfo);
        /** @var StateMachine $stateMachine */
        $stateMachine = $this->serviceContainer->get('state_machine.order_form');
        $stateMachine->apply($order, OrderForm::TRANSITION_REFUNDING);

        $this->updatePayData($apiResponse->getData());
    }

    /**
     * Get form url for pay order
     *
     * @param OrderForm $order
     * @return string
     * @throws GetPayFormException
     * @throws \PaymentBundle\Exceptions\ApiGetDataException
     * @throws \PaymentBundle\Exceptions\ApiGetErrorsDataException
     * @throws \PaymentBundle\Exceptions\PaymentErrorOccurredException
     */
    public function getFormUrlForPay(OrderForm $order)
    {
        if ($order->getPayFormToken() && $order->getStatus() == OrderForm::STATUS_PAYING) {
            $token = $order->getPayFormToken();
        } else {
            $token = $this->getPaymentFormToken($order);
        }
        return $this->paymentService->makeFormUrlFromToken($token);
    }

    /**
     * Get payment form token
     *
     * @param OrderForm $order
     * @return string
     * @throws GetPayFormException
     * @throws \PaymentBundle\Exceptions\ApiGetDataException
     * @throws \PaymentBundle\Exceptions\ApiGetErrorsDataException
     * @throws \PaymentBundle\Exceptions\PaymentErrorOccurredException
     */
    private function getPaymentFormToken(OrderForm $order)
    {
        $userInfo = new UserInfo();
        $userInfo->setCustomerEmail('email@sdf.ru')
            ->setGeoCountry('GBR')
            ->setIpAddress('8.8.8.8');

        $orderInfo = new OrderInfo();
        $orderInfo->setAmount($order->getPrice()->getAmountInPennies())
            ->setCurrency($order->getPrice()->getCurrency())
            ->setOrderDescription('desc')
            ->setOrderId($order->getId());

        try {
            $paymentToken = $this->paymentService->getFormToken($userInfo, $orderInfo);
            $order->setPayFormToken($paymentToken);

            /** @var StateMachine $stateMachine */
            $stateMachine = $this->serviceContainer->get('state_machine.order_form');
            $stateMachine->apply($order, OrderForm::TRANSITION_PAY);

            $this->doctrine->getManager()->persist($order);
            $this->doctrine->getManager()->flush();

        } catch (PaymentTokenFormException $exception) {
            throw new GetPayFormException('Error occurred while get payment form token', 0, $exception);
        } catch (\LogicException $workflowException) {
            throw new GetPayFormException('Invalid state of order for pay operation', 0, $workflowException);
        }

        return $paymentToken;
    }
}