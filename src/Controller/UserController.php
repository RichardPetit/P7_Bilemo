<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function __invoke(): JsonResponse
    {
        return $this->json('test user');
    }
//    public function __invoke(UserRepository $userRepository)
//    {
//        return $userRepository->findAll();
//    }
}
