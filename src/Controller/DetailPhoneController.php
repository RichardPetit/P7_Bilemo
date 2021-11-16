<?php

namespace App\Controller;

use App\Entity\Phone;
use OpenApi\Annotations\JsonContent;
use OpenApi\Annotations\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;


/**
 * @Route("/phones/{id}", name="phone_detail", methods={"GET"})
 * @OA\Get (
 *     path="/phones/{id}",
 *     security={"bearer"},
 *     @Parameter(
 *         name="id",
 *         in="path",
 *         description="ID du mobile",
 *         required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *     @OA\Response(
 *          response="200",
 *          description="Détails d'un mobile",
 *          @JsonContent(ref="#/components/schemas/Phone")
 *     ),
 *     @OA\Response(response=404, description="Le mobile n'existe pas"),
 *     @OA\Response(response=401, description="Jeton d'authentification invalide")
 * )
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
