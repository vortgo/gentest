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
    /**
     * Transform data to array
     *
     * @return array
     * @throws \ReflectionException
     */
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

    /**
     * Get array witch included only selected keys
     *
     * @param array $keys
     * @return array
     * @throws \ReflectionException
     */
    public function only(array $keys)
    {
        $data = $this->toArray();
        return array_filter($data, function ($key) use ($keys) {
            return in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get array witch included all keys except selected
     *
     * @param array $keys
     * @return array
     * @throws \ReflectionException
     */
    public function except(array $keys)
    {
        $data = $this->toArray();
        return array_filter($data, function ($key) use ($keys) {
            return !in_array($key, $keys);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get getter method name by property
     *
     * @param string $property
     * @return string
     */
    private function getNameOfGetter(string $property)
    {
        return 'get' . str_replace('_', '', ucwords($property, '_'));
    }
}