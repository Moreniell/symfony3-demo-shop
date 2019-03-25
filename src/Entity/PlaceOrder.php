<?php

namespace App\Entity;


use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class PlaceOrder
{
    private $customer_name = '';
    private $phone_number = '';
    private $email = '';
    private $address = '';
    private $ordered_products = '';

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('customer_name', new NotBlank())
                 ->addPropertyConstraints('phone_number', [new NotBlank(), new Regex([
                     'pattern' => '/^\+?[\d\s]*\(?[\d\s]{2,}\)?[\d\s-]+$/',
                     'message' => 'The phone number you entered is not valid.'
                 ])])
                 ->addPropertyConstraints('email', [new NotBlank(), new Email()])
                 ->addPropertyConstraint('address', new NotBlank());
    }

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): self
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getOrderedProducts(): ?string
    {
        return $this->ordered_products;
    }

    public function setOrderedProducts($ordered_products): self
    {
        $this->ordered_products = $ordered_products;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }
}