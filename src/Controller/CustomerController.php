<?php

namespace App\Controller;

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
}
