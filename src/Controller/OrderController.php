<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends Controller
{
    /**
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function indexAction() {
        $templating = $this->container->get('templating');
        $html = $templating->render('order/index.html.twig');
        return new Response($html);
    }

    /**
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function cartAction() {
        $templating = $this->container->get('templating');
        $html = $templating->render('order/cart.html.twig');
        return new Response($html);
    }

    /**
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function placedAction() {
        $templating = $this->container->get('templating');
        $html = $templating->render('order/placed.html.twig');
        return new Response($html);
    }
}