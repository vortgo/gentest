<?php

namespace ShowcaseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embedded;

/**
 * OrderForm
 *
 * @ORM\Table(name="order_forms")
 * @ORM\Entity(repositoryClass="ShowcaseBundle\Repository\OrderFormRepository")
 * @ORM\HasLifecycleCallbacks
 */
class OrderForm
{
    const STATUS_CREATED = 'created';
    const STATUS_PAYING = 'paying';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAIL = 'fail';

    const TRANSITION_PAY = 'pay';
    const TRANSITION_PAID = 'paid';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @Embedded(class="Money")
     */
    private $price;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="OrderFormItem", mappedBy="orderForm", cascade={"persist"})
     */
    private $orderFormsItems;

    /**
     * @var string
     *
     * @ORM\Column(name="pay_form_token", type="text",  nullable=true)
     */
    private $payFormToken;

    /**
     * OrderFormItem constructor.
     */
    public function __construct()
    {
        $this->status = self::STATUS_CREATED;
        $this->orderFormsItems = new ArrayCollection();
    }

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
     * Set status
     *
     * @param string $status
     *
     * @return OrderForm
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set price
     *
     * @param Money $price
     *
     * @return OrderForm
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
     * Get order form items
     *
     * @return array
     */
    public function getOrderFormItems()
    {
        return $this->orderFormsItems->toArray();
    }

    /**
     * Add order form item
     *
     * @param OrderFormItem $orderFormsItem
     * @return $this
     */
    public function addOrderFormItem(OrderFormItem $orderFormsItem)
    {
        if (!$this->orderFormsItems->contains($orderFormsItem)) {
            $this->orderFormsItems->add($orderFormsItem);
            $orderFormsItem->setOrderForm($this);
        }

        return $this;
    }

    /**
     * Remove order form item
     *
     * @param OrderFormItem $orderFormsItem
     * @return $this
     */
    public function removeOrderFormItem(OrderFormItem $orderFormsItem)
    {
        if ($this->orderFormsItems->contains($orderFormsItem)) {
            $this->orderFormsItems->removeElement($orderFormsItem);
            $orderFormsItem->setOrderForm(null);
        }

        return $this;
    }


    /**
     * Gets triggered only on insert
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }

    /**
     * @return string
     */
    public function getPayFormToken(): ?string
    {
        return $this->payFormToken;
    }

    /**
     * @param string $payFormToken
     * @return OrderForm
     */
    public function setPayFormToken(string $payFormToken): OrderForm
    {
        $this->payFormToken = $payFormToken;
        return $this;
    }
}

