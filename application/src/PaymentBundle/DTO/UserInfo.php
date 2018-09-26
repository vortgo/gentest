<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 12:43
 */

namespace PaymentBundle\DTO;


class UserInfo extends BaseDTO
{
    /** @var string */
    private $ip_address;
    /** @var string */
    private $geo_country;
    /** @var string */
    private $platform = 'WEB';
    /** @var string */
    private $customer_email;

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    /**
     * @param string $ip_address
     * @return UserInfo
     */
    public function setIpAddress(string $ip_address): UserInfo
    {
        $this->ip_address = $ip_address;
        return $this;
    }

    /**
     * @return string
     */
    public function getGeoCountry(): string
    {
        return $this->geo_country;
    }

    /**
     * @param string $geo_country
     * @return UserInfo
     */
    public function setGeoCountry(string $geo_country): UserInfo
    {
        $this->geo_country = $geo_country;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @param string $platform
     * @return UserInfo
     */
    public function setPlatform(string $platform): UserInfo
    {
        $this->platform = $platform;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    /**
     * @param string $customer_email
     * @return UserInfo
     */
    public function setCustomerEmail(string $customer_email): UserInfo
    {
        $this->customer_email = $customer_email;
        return $this;
    }
}