<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="customer", methods={"GET"})
     */
    public function index(CustomerRepository $customerRepository, NormalizerInterface $normalizer): Response
    {
        $customers = $customerRepository->findBy(['user' => $this->getUser()]);
        $response = $normalizer->normalize($customers, 'array', [AbstractNormalizer::GROUPS => ['customerList', Phone::GROUP_DETAIL]]);

        return $this->json( $response);
    }

}
