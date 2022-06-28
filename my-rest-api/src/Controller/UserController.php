<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list')]
    public function index(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
//        print_r($request->query->all());
        $page = intval($request->query->get('page', 1));
        $limit = intval($request->query->get('limit', 1));
        $criteria = $request->query->all();

        $product = $doctrine->getRepository(User::class)->findByFilter($criteria, $page, $limit);
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found!'
            );
        }

        return $this->json([
            'data'  => $product,
            'page'  => $page,
            'limit'  => $limit,
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_show")
     */
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $product = $doctrine->getRepository(User::class)->find($id);
        if (!$product) {
            return $this->json(new \stdClass());
        }

        return $this->json([
            'id' => $product->getId(),
            'username' => $product->getUsername(),
            'created_at' => $product->getCreatedAt(),
        ]);
    }
}
