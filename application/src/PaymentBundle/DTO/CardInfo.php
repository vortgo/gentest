<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 12:43
 */

namespace PaymentBundle\DTO;


class CardInfo extends BaseDTO
{
    /** @var string */
    private $card_cvv;
    /** @var string */
    private $card_exp_month;
    /** @var string */
    private $card_exp_year;
    /** @var string */
    private $card_holder;
    /** @var string */
    private $card_number;
    /** @var string */
    private $recurring_token;

    /**
     * @return string
     */
    public function getCardCvv(): ?string
    {
        return $this->card_cvv;
    }

    /**
     * @param string $card_cvv
     * @return CardInfo
     */
    public function setCardCvv(string $card_cvv): CardInfo
    {
        $this->card_cvv = $card_cvv;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardExpMonth(): ?string
    {
        return $this->card_exp_month;
    }

    /**
     * @param string $card_exp_month
     * @return CardInfo
     */
    public function setCardExpMonth(string $card_exp_month): CardInfo
    {
        $this->card_exp_month = $card_exp_month;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardExpYear(): ?string
    {
        return $this->card_exp_year;
    }

    /**
     * @param string $card_exp_year
     * @return CardInfo
     */
    public function setCardExpYear(string $card_exp_year): CardInfo
    {
        $this->card_exp_year = $card_exp_year;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardHolder(): ?string
    {
        return $this->card_holder;
    }

    /**
     * @param string $card_holder
     * @return CardInfo
     */
    public function setCardHolder(string $card_holder): CardInfo
    {
        $this->card_holder = $card_holder;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardNumber(): ?string
    {
        return $this->card_number;
    }

    /**
     * @param string $card_number
     * @return CardInfo
     */
    public function setCardNumber(string $card_number): CardInfo
    {
        $this->card_number = $card_number;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecurringToken(): ?string
    {
        return $this->recurring_token;
    }

    /**
     * @param string $recurring_token
     * @return CardInfo
     */
    public function setRecurringToken(string $recurring_token): CardInfo
    {
        $this->recurring_token = $recurring_token;
        return $this;
    }

}