<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {


        return $this->json( $userRepository->findAll(),200, []);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/user", name="user_create", methods={"POST"} )
     */
    public function createAction(Request $request,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $em,
                                 ValidatorInterface $validator)
    {
        $data = $request->getContent();

        try {
            $user = $serializer->deserialize($data, User::class, 'json');


            $error = $validator->validate($user);
            if (count($error) > 0) {
                return $this->json($error, 400);
            }
            $user->setCreatedAt(new \DateTimeImmutable());
            $em->persist($user);
            $em->flush();

            return $this->json($user, 201, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }


//    /**
//     * @Route("/user", name="user")
//     */
//    public function __invoke(User $user): JsonResponse
//    {
//        $data = $this->get('jms_serializer')->serialize($user, 'json',
//        SerializationContext::create()->setGroups(array('userDetail')));
//
//        $response = new JsonResponse($data);
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;
////        return $this->json('test user');
//    }
////    public function __invoke(UserRepository $userRepository)
////    {
////        return $userRepository->findAll();
////    }
}

