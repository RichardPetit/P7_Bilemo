<?php

namespace App\Controller;

use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/phones/{id}", name="phone_detail", methods={"GET"})
 */
class DetailPhoneController extends AbstractController
{

    public function __invoke( NormalizerInterface $normalizer, Phone $phone = null): Response
    {
        if ($phone === null) {
            return $this->json('Phone not found', 404);
        }

        $response = $normalizer->normalize($phone, 'array', [AbstractNormalizer::GROUPS => ['phoneDetail']]);

        return $this->json( $response);
    }
}
