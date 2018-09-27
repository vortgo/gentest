<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 14:27
 */

namespace PaymentBundle\Entity;


use PaymentBundle\Exceptions\ApiGetDataException;
use PaymentBundle\Exceptions\ApiGetErrorsDataException;

class ApiResponse extends AbstractBaseResponse
{
    /**
     * Check is failed response
     * @return bool
     */
    public function isFailed()
    {
        if (isset($this->data['error']) && !empty($this->data)) {
            return true;
        }

        return false;
    }

    /**
     * Get error from response
     *
     * @return mixed
     * @throws ApiGetErrorsDataException
     */
    public function getErrors()
    {
        if (!isset($this->data['error']['messages'])) {
            throw new ApiGetErrorsDataException('Only failed response has errors');
        }
        return $this->data['error']['messages'];
    }

    /**
     * Get errors code
     *
     * @return mixed
     * @throws ApiGetErrorsDataException
     */
    public function getErrorCode()
    {
        if (!isset($this->data['error']['code'])) {
            throw new ApiGetErrorsDataException('Only failed response has errors');
        }
        return $this->data['error']['code'];
    }

    /**
     * Get form token
     *
     * @return mixed
     * @throws ApiGetDataException
     */
    public function getFormToken()
    {
        if (!isset($this->data['pay_form']['token'])) {
            throw new ApiGetDataException('Only success response from init-payment request has a form token ');
        }
        return $this->data['pay_form']['token'];
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}