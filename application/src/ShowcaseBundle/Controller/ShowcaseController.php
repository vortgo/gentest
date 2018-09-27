<?php

namespace ShowcaseBundle\Controller;

use PaymentBundle\DTO\CardInfo;
use PaymentBundle\DTO\OrderInfo;
use PaymentBundle\DTO\UserInfo;
use PaymentBundle\Services\PaymentService;
use ShowcaseBundle\Entity\Card;
use ShowcaseBundle\Entity\Item;
use ShowcaseBundle\Entity\Money;
use ShowcaseBundle\Entity\OrderForm;
use ShowcaseBundle\Entity\OrderFormItem;
use ShowcaseBundle\Exceptions\GetPayFormException;
use ShowcaseBundle\Repository\ItemRepository;
use ShowcaseBundle\Services\OrderService;
use Signedpay\API\Api;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowcaseController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findAll();
        return $this->render('@Showcase/index.html.twig', compact('items'));
    }

    /**
     * @Route("/order/create", methods={"POST"}, name="order_create")
     */
    public function createOrderAction(Request $request)
    {
        /** @var Item $item */
        $item = $this->getDoctrine()->getRepository(Item::class)->find($request->get('item_id'));
        if (!$item) {
            return new Response('', 404);
        }
        /** @var OrderService $orderService */
        $orderService = $this->container->get('app.order_service');
        $orderService->createOrder($item);
        return $this->redirectToRoute('orders_list');
    }

    /**
     * @Route("/orders", name="orders_list")
     */
    public function orderListAction()
    {
        $orders = $this->getDoctrine()->getRepository(OrderForm::class)->findBy([], ['id' => 'DESC']);
        $usersCards = $this->getDoctrine()->getRepository(Card::class)->findBy([], ['id' => 'DESC']);
        return $this->render('@Showcase/orders.html.twig', compact('orders', 'usersCards'));
    }

    /**
     * @Route("/order/{order}/pay", methods={"GET"}, name="pay_form")
     */
    public function orderPayAction($order)
    {
        /** @var OrderForm $orderForm */
        $orderForm = $this->getDoctrine()->getRepository(OrderForm::class)->find($order);
        if (!$orderForm) {
            return new Response('', 404);
        }

        /** @var OrderService $orderService */
        $orderService = $this->container->get('app.order_service');
        $formUrl = $orderService->getFormUrlForPay($orderForm);
        return $this->render('@Showcase/pay.html.twig', compact('formUrl'));
    }

    /**
     * @Route("/order/{order}/recurring/{card}", methods={"GET"}, name="pay_recurring")
     */
    public function orderPayRecurringAction($order, $card)
    {
        /** @var OrderForm $orderForm */
        $orderForm = $this->getDoctrine()->getRepository(OrderForm::class)->find($order);
        /** @var Card $card */
        $card = $this->getDoctrine()->getRepository(Card::class)->find($card);
        if (!$orderForm || !$card) {
            return new Response('', 404);
        }

        /** @var OrderService $orderService */
        $orderService = $this->container->get('app.order_service');
        $orderService->payRecurringOrder($card, $orderForm);
        return $this->redirectToRoute('orders_list');
    }

    /**
     * @Route("/payment/callback")
     */
    public function paymentCallbackAction(Request $request)
    {
        /** @var OrderService $orderService */
        $orderService = $this->container->get('app.order_service');
        $orderService->updatePayData(json_decode($request->getContent(), true));
        return new Response('ok');

    }


}


