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
     * @ORM\OneToMany(targetEntity="App\Entity\OrderedProduct", mappedBy="order", cascade={"persist"}, orphanRemoval=true)
     */
    private $ordered_products;
    

    public function __construct()
    {
        $this->ordered_products = new ArrayCollection();
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

    /**
     * @return Collection|OrderedProduct[]
     */
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
}
