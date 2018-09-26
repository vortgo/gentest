<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 23:02
 */

namespace ShowcaseBundle\Services;


use Doctrine\Common\Persistence\ObjectManager;
use PaymentBundle\DTO\UserInfo;
use PaymentBundle\Services\PaymentService;
use ShowcaseBundle\Entity\Item;
use ShowcaseBundle\Entity\OrderForm;

class OrderService
{
    /** @var PaymentService */
    private $paymentService;
    /** @var ObjectManager */
    private $objectManager;

    public function __construct($paymentService, $objectManager)
    {
        $this->paymentService = $paymentService;
        $this->objectManager = $objectManager;
    }

    public function createOrder(Item $item)
    {
        $order = new OrderForm();
        $order->setPrice($item->getPrice());
        $this->objectManager->persist($order);
        $this->objectManager->flush();
        return $order;
    }

    public function getFormUrlForPay(OrderForm $order)
    {
        $userInfo = new UserInfo();
        $userInfo->setCustomerEmail('email@sdf.ru')
            ->setGeoCountry('GBR')
            ->setIpAddress('8.8.8.8');

        $orderInfo = new OrderInfo();
        $orderInfo->setAmount(1000)
            ->setCurrency('USD')
            ->setOrderDescription('desc')
            ->setOrderId(900);

        $this->paymentService->initPayment();
    }
}