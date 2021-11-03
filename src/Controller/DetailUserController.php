<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/users/{id}", name="users_detail", methods={"GET"})
 */
class DetailUserController extends AbstractController
{
    public function __invoke(User $user, NormalizerInterface $normalizer): Response
    {

        $response = $normalizer->normalize($user, 'array', [AbstractNormalizer::GROUPS => ['userDetail']]);

        return $this->json($response);
    }
}
