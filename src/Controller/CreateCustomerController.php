<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @param Request $request
 * @param SerializerInterface $serializer
 * @param EntityManagerInterface $em
 * @param ValidatorInterface $validator
 * @return JsonResponse
 * @Route("/customers", name="customer_create", methods={"POST"} )
 */
class CreateCustomerController extends AbstractController
{
    public function __invoke(Request $request,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $em,
                                 NormalizerInterface $normalizer,
                                 ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();

        try {
            $customer = $serializer->deserialize($data, Customer::class, 'json');
            $customer->setUser($this->getUser());

            $error = $validator->validate($customer);
            if (count($error) > 0) {
                return $this->json($error, 400);
            }
            $em->persist($customer);
            $em->flush();

            $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);
            return $this->json($response, 201);

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
