<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users", name="user", methods={"GET"})
 */
class UserController extends AbstractController
{

    public function __invoke(UserRepository $userRepository): Response
    {
        return $this->json( $userRepository->findAll(),200, []);
    }
}

