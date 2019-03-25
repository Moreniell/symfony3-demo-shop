<?php

namespace App\Repository;

use App\Entity\OrderedProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderedProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderedProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderedProduct[]    findAll()
 * @method OrderedProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderedProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderedProduct::class);
    }
}
