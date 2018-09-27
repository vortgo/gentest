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
        if (isset($this->data['transaction']['card'])) {
            $card = new Card();
            $card->fillFromArray($this->data['transaction']['card']);
            $card->setToken($this->data['transaction']['card']['card_token']['token'] ?? null);
            return $card;
        }
        throw new ApiGetDataException('Response does not have a card section');
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
}