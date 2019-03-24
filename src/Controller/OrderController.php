<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\PlaceOrder;
use App\Entity\Product;
use App\Form\PlaceOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $cart_items_prefix = 'cart_product_';
        $orderedGoods = [];
        foreach ($_COOKIE as $key => $value) {
            if (substr($key, 0, strlen($cart_items_prefix)) === $cart_items_prefix) {
                $orderedGoods[] = json_decode($value);
            }
        }
        $task = new PlaceOrder();
        $form = $this->createFormBuilder($task)
            ->add('address', TextType::class)
            ->add('place_order', SubmitType::class, ['label' => 'Place Order'])
            ->getForm();

        $form->handleRequest($request);

        $templating = $this->container->get('templating');
        $params = [
            'place_order_form' => $form->createView()
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            $order = new Order();
            $order->setAddress($form->getData()->getAddress());
            foreach ($orderedGoods as $product) {
                if ($product->count < 1) {
                    continue;
                }

                $p = $this->getDoctrine()->getRepository(Product::class)->find($product->pid);
                if (!$p) {
                    throw $this->createNotFoundException(
                        'No product found for id '.$product->pid
                    );
                }

                $orderedProduct = new OrderedProduct();
                $orderedProduct->setProduct($p);
                $orderedProduct->setCount($product->count);
                $order->addOrderedProduct($orderedProduct);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();

            foreach ($_COOKIE as $key => $value) {
                if (substr($key, 0, strlen($cart_items_prefix)) === $cart_items_prefix) {
                    setcookie($key, "", time()-3600);
                }
            }

            return new RedirectResponse($this->generateUrl('order_state_placed'), Response::HTTP_MOVED_PERMANENTLY);
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