<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    /**
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function indexAction() {
        $productRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);

        $templating = $this->container->get('templating');
        $html = $templating->render('home/index.html.twig', [
            'products' => $productRepo->findAll()
        ]);
        return new Response($html);
    }
}