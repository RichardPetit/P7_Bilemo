<?php

namespace App\Controller;

use App\Entity\Phone;
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
 * @Route("/phone", name="phone_create", methods={"POST"})
 */
class CreatePhoneController extends AbstractController
{
    public function __invoke(Request            $request, SerializerInterface $serializer, EntityManagerInterface $em,
                             ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();
        try {
            $phone = $serializer->deserialize($data, Phone::class, 'json');

            $error = $validator->validate($phone);
            if (count($error) > 0) {
                return $this->json($error, 400);
            }
            $em->persist($phone);
            $em->flush();


            return $this->json($phone, 201, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
