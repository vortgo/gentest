<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 27.09.18
 * Time: 12:24
 */

namespace PaymentBundle\Entity;


use PaymentBundle\Exceptions\ApiGetDataException;
use PaymentBundle\Entity\ResponseEntities\Card;
use PaymentBundle\Entity\ResponseEntities\Order;

class CallbackResponse extends AbstractBaseResponse
{
    /**
     * @return Order
     * @throws ApiGetDataException
     */
    public function getOrder()
    {
        if (isset($this->data['order'])) {
            $order = new Order();
            $order->fillFromArray($this->data['order']);
            return $order;
        }
        throw new ApiGetDataException('Response does not have a order section');
    }

    /**
     * @return Card
     * @throws ApiGetDataException
     */
    public function getCard()
    {
        $card = new Card();
        if (isset($this->data['transaction']['card'])) {
            $card->fillFromArray($this->data['transaction']['card']);
            $card->setToken($this->data['transaction']['card']['card_token']['token'] ?? null);
            return $card;
        }
        return $card;
    }

    /**
     * @return mixed
     * @throws ApiGetDataException
     */
    public function getPaymentStatus()
    {
        if (isset($this->data['transaction']['status'])) {
            return $this->data['transaction']['status'];
        }
        throw new ApiGetDataException('Invalid response');
    }

    /**
     * @return string
     * @throws ApiGetDataException
     */
    public function getPaymentOperation()
    {
        if (isset($this->data['transaction']['operation'])) {
            return $this->data['transaction']['operation'];
        }
        throw new ApiGetDataException('Invalid response');
    }
}