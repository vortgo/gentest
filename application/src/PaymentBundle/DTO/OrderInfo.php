<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 12:43
 */

namespace PaymentBundle\DTO;


class OrderInfo extends BaseDTO
{
    /** @var int */
    private $amount;
    /** @var string */
    private $currency;
    /** @var string */
    private $order_description;
    /** @var string */
    private $order_id;

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return OrderInfo
     */
    public function setAmount(float $amount): OrderInfo
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return OrderInfo
     */
    public function setCurrency(string $currency): OrderInfo
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderDescription(): string
    {
        return $this->order_description;
    }

    /**
     * @param string $order_description
     * @return OrderInfo
     */
    public function setOrderDescription(string $order_description): OrderInfo
    {
        $this->order_description = $order_description;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->order_id;
    }

    /**
     * @param string $order_id
     * @return OrderInfo
     */
    public function setOrderId(string $order_id): OrderInfo
    {
        $this->order_id = $order_id;
        return $this;
    }
}