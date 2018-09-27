<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 27.09.18
 * Time: 12:24
 */

namespace PaymentBundle\Entity\ResponseEntities;


abstract class AbstractEntity
{
    public function fillFromArray(array $data)
    {
        foreach ($data as $property => $value) {
            $this->{$property} = $value;
        }
    }
}