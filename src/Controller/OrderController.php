<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderedProduct;
use App\Entity\PlaceOrder;
use App\Entity\Product;
use App\Form\PlaceOrderType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
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
            ->add('customer_name', TextType::class)
            ->add('phone_number', TelType::class)
            ->add('email', EmailType::class)
            ->add('address', TextType::class)
            ->add('place_order', SubmitType::class, ['label' => 'Place Order'])
            ->getForm();

        $form->handleRequest($request);

        $templating = $this->container->get('templating');
        $params = [
            'place_order_form' => $form->createView()
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var PlaceOrder $placedOrder */
            $placedOrder = $form->getData();
            $order = new Order();
            $order->setCustomerName($placedOrder->getCustomerName());
            $order->setPhoneNumber(preg_replace("/[^0-9\+]/", "", $placedOrder->getPhoneNumber()));
            $order->setEmail($placedOrder->getEmail());
            $order->setAddress($placedOrder->getAddress());

            $totalSum = 0;
            foreach ($orderedGoods as $ordered) {
                if ($ordered->count < 1) {
                    continue;
                }

                $product = $this->getDoctrine()->getRepository(Product::class)->find($ordered->pid);
                if (!$product) {
                    throw $this->createNotFoundException(
                        'No product found for id '.$ordered->pid
                    );
                }

                $totalSum += $product->getPrice();

                $orderedProduct = new OrderedProduct();
                $orderedProduct->setProduct($product);
                $orderedProduct->setCount($ordered->count);
                $order->addOrderedProduct($orderedProduct);
            }
            $order->setTotalSum($totalSum);
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