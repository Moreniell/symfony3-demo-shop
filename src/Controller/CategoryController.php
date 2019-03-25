<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CategoryController extends Controller
{
    /**
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function indexAction() {
        $categoriesRepo = $this->getDoctrine()->getManager()->getRepository(Category::class);

        $templating = $this->container->get('templating');
        $html = $templating->render('category/index.html.twig', [
            'categories' => $categoriesRepo->findAll()
        ]);
        return new Response($html);
    }

    /**
     * @param $id
     * @return Response
     * @throws \Twig\Error\Error
     */
    public function catalogAction($id) {
        $categoriesRepo = $this->getDoctrine()->getManager()->getRepository(Category::class);
        $selectedCategory = $categoriesRepo->find($id);

        if (!$selectedCategory) {
            throw new NotFoundHttpException('Catalog with specified id not found');
        }

        $productRepo = $this->getDoctrine()->getManager()->getRepository(Product::class);

        $templating = $this->container->get('templating');
        $html = $templating->render('category/catalog.html.twig', [
            'category' => $selectedCategory,
            'products' => $productRepo->findByCategoryId($id)
        ]);
        return new Response($html);
    }
}