<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;


/**
 * @Route("/customers/{id}", name="customer_detail", methods={"GET"})
 * @OA\Get (
 *     path="/customers/{id}",
 *     security={"bearer"},
 *     @Parameter(
 *         name="id",
 *         in="path",
 *         description="ID du client",
 *         required=true,
 *         @OA\Schema(type="integer")
 *      ),
 *     @OA\Response(
 *          response="200",
 *          description="Détails d'un client",
 *          @OA\JsonContent(ref="#/components/schemas/Customer")
 *     ),
 *     @OA\Response(response=404, description="Le client n'existe pas"),
 *     @OA\Response(response=401, description="Jeton d'authentification invalide"),
 *     @OA\Response(response=403, description="L'accès à cette page ne vous est pas autorisé")
 * )
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
