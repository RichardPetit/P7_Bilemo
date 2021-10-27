<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PhoneController extends AbstractController
{
    /**
     * @Route("/phone", name="phone")
     */
    public function __invoke(Phone $phone): JsonResponse
    {
        $data = $this->get('jms_serializer')->serialize($phone, 'json',
            SerializationContext::create()->setGroups(array('phoneDetail')));

        $response = new JsonResponse($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
//        return $this->json('test phone');
    }
//    public function __invoke(PhoneRepository $phoneRepository): JsonResponse
//    {
//        return $phoneRepository->findAll();
//    }


}
