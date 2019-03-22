<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class HomeController extends Controller
{
    /**
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function indexAction() {
        $templating = $this->container->get('templating');
        $html = $templating->render('home/index.html.twig');
        return new Response($html);
    }
}