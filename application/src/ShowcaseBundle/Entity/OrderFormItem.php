<?php

namespace ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;

/**
 * OrderFormItem
 *
 * @ORM\Table(name="order_form_item")
 * @ORM\Entity(repositoryClass="ShowcaseBundle\Repository\OrderFormItemRepository")
 */
class OrderFormItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @Embedded(class="Money")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="order_form_item")
     * @ORM\JoinColumn(name="item_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $item;

    /**
     * @ORM\ManyToOne(targetEntity="OrderForm", inversedBy="order_form_item")
     * @ORM\JoinColumn(name="order_form_id", referencedColumnName="id", nullable=FALSE)
     */
    protected $orderForm;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OrderFormItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param Money $price
     *
     * @return OrderFormItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return Money
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param Item $item
     * @return OrderFormItem
     */
    public function setItem(Item $item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderForm()
    {
        return $this->orderForm;
    }

    /**
     * @param OrderForm $orderForm
     * @return OrderFormItem
     */
    public function setOrderForm(OrderForm $orderForm)
    {
        $this->orderForm = $orderForm;
        return $this;
    }
}

