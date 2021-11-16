<?php

namespace App\Controller;

use App\Repository\PhoneRepository;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\JsonContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

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
class PhoneController extends AbstractController
{

    public function __invoke(PhoneRepository $phoneRepository): Response
    {
        return $this->json( $phoneRepository->findAll(),200, []);
    }

}
