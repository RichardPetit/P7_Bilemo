<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    /**
     * @Route("/phone", name="phone")
     */
    public function __invoke(): JsonResponse
    {
        return $this->json('test phone');
    }
//    public function __invoke(PhoneRepository $phoneRepository): JsonResponse
//    {
//        return $phoneRepository->findAll();
//    }


}
