<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializationContext;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function __invoke(User $user): JsonResponse
    {
        $data = $this->get('jms_serializer')->serialize($user, 'json',
        SerializationContext::create()->setGroups(array('userDetail')));

        $response = new JsonResponse($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
//        return $this->json('test user');
    }
//    public function __invoke(UserRepository $userRepository)
//    {
//        return $userRepository->findAll();
//    }
}
