<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $customer_name;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="float")
     */
    private $total_sum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedProduct", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    private $ordered_products;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_complete;
    

    public function __construct()
    {
        $this->ordered_products = new ArrayCollection();
        $this->is_complete = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customer_name;
    }

    public function setCustomerName($customer_name): self
    {
        $this->customer_name = $customer_name;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber($phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTotalSum() : ?float
    {
        return $this->total_sum;
    }

    public function setTotalSum($total_sum): self
    {
        $this->total_sum = $total_sum;

        return $this;
    }

    public function getOrderedProducts(): Collection
    {
        return $this->ordered_products;
    }

    public function addOrderedProduct(OrderedProduct $orderedProduct): self
    {
        if (!$this->ordered_products->contains($orderedProduct)) {
            $this->ordered_products[] = $orderedProduct;
            $orderedProduct->setOrder($this);
        }

        return $this;
    }

    public function removeOrderedProduct(OrderedProduct $orderedProduct): self
    {
        if ($this->ordered_products->contains($orderedProduct)) {
            $this->ordered_products->removeElement($orderedProduct);
            // set the owning side to null (unless already changed)
            if ($orderedProduct->getOrder() === $this) {
                $orderedProduct->setOrder(null);
            }
        }

        return $this;
    }

    public function getIsComplete(): ?bool
    {
        return $this->is_complete;
    }

    public function setIsComplete($is_complete): self
    {
        $this->is_complete = $is_complete;

        return $this;
    }
}
