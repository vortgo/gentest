<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 12:33
 */

namespace PaymentBundle\Interfaces;


use PaymentBundle\DTO\Payload;

interface PaymentInterface
{
    public function charge(Payload $payload);

    public function recurring(Payload $payload);

    public function refund();

    public function update();
}