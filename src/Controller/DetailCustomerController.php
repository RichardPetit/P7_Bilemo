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

    public function __invoke(NormalizerInterface $normalizer, Customer $customer = null): Response
    {
        if ($customer === null) {
            return $this->json('Customer not found', 404);
        }

        if ($customer->getUser() !== $this->getUser()){
            return $this->json('No authorization', 403);

        }

        $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);

        return $this->json( $response);
    }
}
