<?php

namespace ShowcaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Embeddable;

/**
 * Money
 * @Embeddable
 */
class Money
{
    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=3)
     */
    protected $currency;

    /**
     * Money constructor.
     * @param float $amount
     * @param string $currency
     */
    public function __construct(float $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function __toString()
    {
        return $this->amount . ' ' . $this->currency;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmountInPennies()
    {
        return (int)$this->amount * 100;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}

