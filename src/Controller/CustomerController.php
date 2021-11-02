<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Phone;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="customer", methods={"GET"})
     */
    public function index(CustomerRepository $customerRepository, NormalizerInterface $normalizer): Response
    {
//        $customers = $this->getUser()->getCustomer();

        $customers = $customerRepository->findAll();
        $response = $normalizer->normalize($customers, 'array', [AbstractNormalizer::GROUPS => ['customerList', Phone::GROUP_DETAIL]]);

        return $this->json( $response);
    }

    /**
     * @Route("/customers/{id}", name="customer_detail", methods={"GET"})
     */
    public function detail(Customer $customer, NormalizerInterface $normalizer): Response
    {
//        $customers = $this->getUser()->getCustomer();

        $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);

        return $this->json( $response);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @Route("/customers", name="customer_create", methods={"POST"} )
     */
    public function createAction(Request $request,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $em,
                                 UserRepository $userRepository,
                                 NormalizerInterface $normalizer,
                                 ValidatorInterface $validator)
    {
        $data = $request->getContent();

        try {
            $customer = $serializer->deserialize($data, Customer::class, 'json');
            $customer->setUser($userRepository->find(1));

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

    /**
     * @Route("/customers/{id}", name="customer_delete", methods={"DELETE"})
     */
    public function delete(Customer $customer, EntityManagerInterface $em): Response
    {
        $em->remove($customer);
        $em->flush();
        return $this->json( '', 204);
    }

}
