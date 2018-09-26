<?php
/**
 * Created by PhpStorm.
 * User: vortgo
 * Date: 26.09.18
 * Time: 12:43
 */

namespace PaymentBundle\DTO;


abstract class BaseDTO
{
    public function toArray()
    {
        $data = [];
        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties();
        foreach ($properties as $property) {
            $getter = $this->getNameOfGetter($property->getName());
            $data[$property->getName()] = $this->{$getter}();
        }
        return $data;
    }

    public function only(array $keys)
    {
        $data = $this->toArray();
        return array_filter($data, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function except(array $keys)
    {
        $data = $this->toArray();
        return array_filter($data, function ($key) use ($keys) {
            return !in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getNameOfGetter(string $property)
    {
        return 'get' . str_replace('_', '', ucwords($property, '_'));
    }
}