<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 27.09.18
 * Time: 12:34
 */

namespace PaymentBundle\Entity\ResponseEntities;

class Card extends AbstractEntity
{
    protected $bank;
    protected $bin;
    protected $brand;
    protected $country;
    protected $number;
    protected $token;

    /**
     * @return mixed
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @return mixed
     */
    public function getBin()
    {
        return $this->bin;
    }

    /**
     * @return mixed
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return Card
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

}