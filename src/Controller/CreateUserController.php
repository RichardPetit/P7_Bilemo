<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @param Request $request
 * @param SerializerInterface $serializer
 * @param EntityManagerInterface $em
 * @param ValidatorInterface $validator
 * @return JsonResponse
 * @Route("/user", name="user_create", methods={"POST"} )
 */
class CreateUserController extends AbstractController
{
    public function __invoke(Request $request,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $em,
                                 ValidatorInterface $validator): JsonResponse
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
}
