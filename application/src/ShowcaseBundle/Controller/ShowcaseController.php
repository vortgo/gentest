<?php

namespace ShowcaseBundle\Controller;

use PaymentBundle\DTO\CardInfo;
use PaymentBundle\DTO\OrderInfo;
use PaymentBundle\DTO\UserInfo;
use PaymentBundle\Services\PaymentService;
use ShowcaseBundle\Entity\Item;
use ShowcaseBundle\Entity\Money;
use ShowcaseBundle\Entity\OrderForm;
use ShowcaseBundle\Entity\OrderFormItem;
use ShowcaseBundle\Repository\ItemRepository;
use ShowcaseBundle\Services\OrderService;
use Signedpay\API\Api;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\StateMachine;

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
     * @Route("/order/create", methods={"POST"}, name="invoice_create")
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
        $orders = $this->getDoctrine()->getRepository(OrderForm::class)->findAll();
        return $this->render('@Showcase/orders.html.twig', compact('orders'));
    }

}
