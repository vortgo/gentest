<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 27.09.18
 * Time: 12:34
 */

namespace PaymentBundle\Entity\ResponseEntities;

class Order extends AbstractEntity
{
    protected $order_id;
    protected $status;
    protected $amount;
    protected $refunded_amount;
    protected $currency;
    protected $marketing_amount;
    protected $marketing_currency;
    protected $processing_amount;
    protected $processing_currency;
    protected $descriptor;
    protected $fraudulent;
    protected $total_fee_amount;
    protected $fee_currency;

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getRefundedAmount()
    {
        return $this->refunded_amount;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return mixed
     */
    public function getMarketingAmount()
    {
        return $this->marketing_amount;
    }

    /**
     * @return mixed
     */
    public function getMarketingCurrency()
    {
        return $this->marketing_currency;
    }

    /**
     * @return mixed
     */
    public function getProcessingAmount()
    {
        return $this->processing_amount;
    }

    /**
     * @return mixed
     */
    public function getProcessingCurrency()
    {
        return $this->processing_currency;
    }

    /**
     * @return mixed
     */
    public function getDescriptor()
    {
        return $this->descriptor;
    }

    /**
     * @return mixed
     */
    public function getFraudulent()
    {
        return $this->fraudulent;
    }

    /**
     * @return mixed
     */
    public function getTotalFeeAmount()
    {
        return $this->total_fee_amount;
    }

    /**
     * @return mixed
     */
    public function getFeeCurrency()
    {
        return $this->fee_currency;
    }

}