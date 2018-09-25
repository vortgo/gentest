<?php

namespace ShowcaseBundle\Controller;

use ShowcaseBundle\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShowcaseController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $items = $this->getDoctrine()->getRepository(Item::class)->findAll();
        return $this->render('@Showcase/Default/index.html.twig', compact('items'));
    }

    /**
     * * @Route("/invoice/create", methods={"POST"}, name="invoice_create")
     */
    public function createInvoiceAction()
    {

    }
}
