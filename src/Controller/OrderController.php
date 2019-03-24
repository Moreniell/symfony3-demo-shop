<?php

namespace App\Controller;

use App\Entity\PlaceOrder;
use App\Form\PlaceOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function indexAction(Request $request) {
        $task = new PlaceOrder();
        $task->setOrderedProducts('{}');
        $form = $this->createFormBuilder($task)
            ->add('ordered_products', HiddenType::class)
            ->add('address', TextType::class)
            ->add('place_order', SubmitType::class, ['label' => 'Place Order'])
            ->getForm();

        $form->handleRequest($request);

        $templating = $this->container->get('templating');
        $params = [
            'place_order_form' => $form->createView()
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            $params['task'] = $form->getData();
        }
        $html = $templating->render('order/index.html.twig', $params);
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