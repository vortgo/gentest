<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 12:41
 */

namespace PaymentBundle\Services;


use PaymentBundle\DTO\CardInfo;
use PaymentBundle\DTO\OrderInfo;
use PaymentBundle\DTO\UserInfo;
use PaymentBundle\Entity\ApiResponse;
use PaymentBundle\Entity\SuccessApiResponse;
use PaymentBundle\Exceptions\PaymentChargeException;
use PaymentBundle\Exceptions\PaymentRecurringException;
use PaymentBundle\Exceptions\PaymentTokenFormException;
use Signedpay\API\Api;

class PaymentService
{
    /** @var Api */
    private $api;

    /**
     * PaymentService constructor.
     *
     * @param string $merchantId
     * @param string $privateKey
     * @param string $apiEndpoint
     */
    public function __construct(string $merchantId, string $privateKey, string $apiEndpoint)
    {
        $this->api = new Api($merchantId, $privateKey, $apiEndpoint);
    }

    public function initPayment(UserInfo $userInfo, OrderInfo $orderInfo, CardInfo $cardInfo)
    {
        $payload = array_merge($userInfo->toArray(), $orderInfo->toArray(), $cardInfo->except(['recurring_token']));
        try {
            $responseData = $this->api->initPayment($payload);
        } catch (\Exception $apiException) {
            throw new PaymentTokenFormException('Init payment failed.', 0, $apiException);
        }
        return $this->makeResponse($responseData);
    }

    public function charge(UserInfo $userInfo, OrderInfo $orderInfo, CardInfo $cardInfo)
    {
        $payload = array_merge($userInfo->toArray(), $orderInfo->toArray(), $cardInfo->except(['recurring_token']));
        try {
            $responseData = $this->api->charge($payload);
        } catch (\Exception $apiException) {
            throw new PaymentChargeException('Payment charge failed.', 0, $apiException);
        }
        return $this->makeResponse($responseData);
    }

    public function recurring(UserInfo $userInfo, OrderInfo $orderInfo, CardInfo $cardInfo)
    {
        $payload = array_merge((array)$userInfo, (array)$orderInfo, (array)$cardInfo);
        try {
            $responseData = $this->api->recurring($payload);
        } catch (\Exception $apiException) {
            throw new PaymentRecurringException('Payment recurring failed.', 0, $apiException);
        }
        return $this->makeResponse($responseData);
    }

    private function makeResponse(array $data)
    {
        return new ApiResponse($data);
    }

}