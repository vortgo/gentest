<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 27.09.18
 * Time: 12:24
 */

namespace PaymentBundle\Entity;


abstract class AbstractBaseResponse
{
    /** @var array */
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}