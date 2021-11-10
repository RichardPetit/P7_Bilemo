<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;



class CustomerController extends AbstractController
{
    /**
     * @Route("/customers", name="customer", methods={"GET"})
     * @OA\Get(
     *     path="/customers",
     *     @OA\Response(
     *          response="200",
     *          description="Liste des clients",
     *          @OA\JsonContent(type="array", @Items(ref="#/components/schemas/Customer")),
     *     )
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton d'authentification invalide")
     *     @OA\Response(response=403, description="L'accès à cette page ne vous est pas autorisé")
     * )
     */
    public function index(CustomerRepository $customerRepository, NormalizerInterface $normalizer): Response
    {
        $customers = $customerRepository->findBy(['user' => $this->getUser()]);
        $response = $normalizer->normalize($customers, 'array', [AbstractNormalizer::GROUPS => ['customerList', Phone::GROUP_DETAIL]]);

        return $this->json( $response);
    }

}
