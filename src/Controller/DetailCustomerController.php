<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/customers/{id}", name="customer_detail", methods={"GET"})
 */
class DetailCustomerController extends AbstractController
{

    public function __invoke(Customer $customer, NormalizerInterface $normalizer): Response
    {

        $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);

        return $this->json( $response);
    }
}
