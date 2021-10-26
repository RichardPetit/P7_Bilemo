<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customer", name="customer")
 */
class CustomerController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        return $this->json('test customer');
    }
//        public function __invoke(CustomerRepository $customerRepository)
//    {
//        return $customerRepository->findAll();
//    }

}
