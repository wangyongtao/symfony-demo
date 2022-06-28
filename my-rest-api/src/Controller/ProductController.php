<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_list')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $product = $doctrine->getRepository(Product::class)->findAll();
        if (!$product) {
            return $this->json([
                'errorCode' => 404,
                'errorMsg' => 'no user found',
            ], 404);
        }

        return $this->json($product);
    }

    /**
     * @Route("/products/{id}", name="product_show")
     */
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $product = $doctrine->getRepository(Product::class)->find($id);
        if (!$product) {
            return $this->json(new \stdClass());
        }

        return $this->json([
            'id' => $product->getId(),
            'name' => $product->getName(),
        ]);
    }
    /**
     * @Route("/products", name="create_product")
     */
    public function createProduct(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id ' . $product->getId());
    }
}
