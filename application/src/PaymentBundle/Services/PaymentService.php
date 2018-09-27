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
use PaymentBundle\Entity\CallbackResponse;
use PaymentBundle\Exceptions\PaymentErrorOccurredException;
use PaymentBundle\Exceptions\PaymentRecurringException;
use PaymentBundle\Exceptions\PaymentRefundingException;
use PaymentBundle\Exceptions\PaymentTokenFormException;
use Signedpay\API\Api;

class PaymentService
{
    /** @var Api */
    private $api;
    /** @var string */
    private $apiEndpoint;
    /** @var string */
    private $paymentCallback;

    /**
     * PaymentService constructor.
     *
     * @param string $merchantId
     * @param string $privateKey
     * @param string $apiEndpoint
     */
    public function __construct(string $merchantId, string $privateKey, string $apiEndpoint, string $paymentCallback)
    {
        $this->api = new Api($merchantId, $privateKey, $apiEndpoint);
        $this->apiEndpoint = $apiEndpoint;
        $this->paymentCallback = $paymentCallback;
    }

    /**
     * Get form token for payment form
     *
     * @param UserInfo $userInfo
     * @param OrderInfo $orderInfo
     * @return mixed
     * @throws PaymentErrorOccurredException
     * @throws PaymentTokenFormException
     * @throws \PaymentBundle\Exceptions\ApiGetDataException
     * @throws \PaymentBundle\Exceptions\ApiGetErrorsDataException
     */
    public function getFormToken(UserInfo $userInfo, OrderInfo $orderInfo)
    {
        $apiResponse = $this->initPayment($userInfo, $orderInfo);
        if ($apiResponse->isFailed()) {
            throw new PaymentErrorOccurredException(\GuzzleHttp\json_encode($apiResponse->getErrors()));
        }
        return $apiResponse->getFormToken();
    }

    /**
     * Make form url
     *
     * @param string $token
     * @return string
     */
    public function makeFormUrlFromToken(string $token)
    {
        return $this->apiEndpoint . 'purchase/' . $token;
    }

    /**
     * Do recurring
     *
     * @param UserInfo $userInfo
     * @param OrderInfo $orderInfo
     * @param CardInfo $cardInfo
     * @return ApiResponse
     * @throws PaymentRecurringException
     * @throws \ReflectionException
     */
    public function recurring(UserInfo $userInfo, OrderInfo $orderInfo, CardInfo $cardInfo)
    {
        $payload = array_merge($userInfo->toArray(), $orderInfo->toArray(), $cardInfo->toArray(), ['callback_url' => $this->paymentCallback]);
        try {
            $responseData = $this->api->recurring($payload);
        } catch (\Exception $apiException) {
            throw new PaymentRecurringException('Payment recurring failed.', 0, $apiException);
        }
        return $this->makeResponse($responseData);
    }

    /**
     * Do refund
     *
     * @param OrderInfo $orderInfo
     * @return ApiResponse
     * @throws PaymentRefundingException
     * @throws \ReflectionException
     */
    public function refund(OrderInfo $orderInfo)
    {
        $payload = array_merge($orderInfo->toArray(), ['callback_url' => $this->paymentCallback]);
        try {
            $responseData = $this->api->refund($payload);
        } catch (\Exception $apiException) {
            throw new PaymentRefundingException('Refund failed');
        }
        return $this->makeResponse($responseData);
    }

    /**
     * Make callback response
     *
     * @param array $data
     * @return CallbackResponse
     */
    public function processCallback(array $data)
    {
        return new CallbackResponse($data);
    }

    /**
     * Initialize payment for payment form
     *
     * @param UserInfo $userInfo
     * @param OrderInfo $orderInfo
     * @return ApiResponse
     * @throws PaymentTokenFormException
     * @throws \ReflectionException
     */
    private function initPayment(UserInfo $userInfo, OrderInfo $orderInfo)
    {
        $payload = array_merge($userInfo->toArray(), $orderInfo->toArray(), ['callback_url' => $this->paymentCallback]);
        try {
            $responseData = $this->api->initPayment($payload);
        } catch (\Exception $apiException) {
            throw new PaymentTokenFormException('Init payment failed.', 0, $apiException);
        }
        return $this->makeResponse($responseData);
    }

    /**
     * Make response
     *
     * @param array $data
     * @return ApiResponse
     */
    private function makeResponse(array $data)
    {
        return new ApiResponse($data);
    }


}