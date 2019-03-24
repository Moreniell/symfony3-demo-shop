<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class ApiController extends Controller
{
    public function getProductByIdAction($id) {
        $productRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(0);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $encoders = [new JsonEncoder()];
        $normalizers = [$normalizer];

        $serializer = new Serializer($normalizers, $encoders);
        $response = json_decode($serializer->serialize($productRepo->find($id), 'json'));

        return new JsonResponse($response);
    }
}