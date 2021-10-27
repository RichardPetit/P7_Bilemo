<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer", name="customer")
 */
class CustomerController extends AbstractController
{
    public function __invoke(Customer $customer): JsonResponse
    {
        $data = $this->get('jms_serializer')->serialize($customer, 'json',
            SerializationContext::create()->setGroups(array('customerDetail')));

        $response = new JsonResponse($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
//        return $this->json('test customer');
    }
//        public function __invoke(CustomerRepository $customerRepository)
//    {
//        return $customerRepository->findAll();
//    }

}
