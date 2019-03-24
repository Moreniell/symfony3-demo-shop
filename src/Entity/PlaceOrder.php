<?php

namespace App\Entity;


class PlaceOrder
{
    private $ordered_products = '';
    private $address = '';

    public function getOrderedProducts(): String
    {
        return $this->ordered_products;
    }

    public function setOrderedProducts($ordered_products): self
    {
        $this->ordered_products = $ordered_products;

        return $this;
    }

    public function getAddress(): String
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }
}