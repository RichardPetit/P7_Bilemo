<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class PhoneController extends AbstractController
{

    /**
     * @Route("/phones", name="phone", methods={"GET"})
     * @OA\Get(
     *     path="/phones",
     *     @OA\Response(
     *          response="200",
     *          description="Liste des mobiles",
     *          @JsonContent(type="array", @Items(ref="#/components/schemas/Phone"))
     *     ),
     *     @OA\Response(response=404, description="Le mobile n'existe pas"),
     *     @OA\Response(response=401, description="Jeton d'authentification invalide"),
     *     @OA\Response(response=403, description="L'accès à cette page ne vous est pas autorisé")
     * )
     */
    public function list(PhoneRepository $phoneRepository): Response
    {
        return $this->json( $phoneRepository->findAll(),200, [] ,[
            AbstractNormalizer::GROUPS => Phone::GROUP_LIST
        ]);
    }

    /**
     * @Route("/phones/{id}", name="phone_detail", methods={"GET"})
     * @OA\Get (
     *     path="/phones/{id}",
     *     security={"bearer"},
     *     @OA\Parameter(
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
    public function details( NormalizerInterface $normalizer, Phone $phone = null): Response
    {
        if ($phone === null) {
            return $this->json('Phone not found', 404);
        }

        $response = $normalizer->normalize($phone, 'array', [AbstractNormalizer::GROUPS => ['phoneDetail']]);

        return $this->json( $response);
    }

}
