<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;


/**
 * @Route("/customers/{id}", name="customer_delete", methods={"DELETE"})
 * @OA\Delete(
 *     path="/customers/{id}",
 *     security={"bearer"},
 *     @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID du client",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *     @OA\Response(
 *          response="204",
 *          description="Suppression d'un client",
 *          @OA\JsonContent(ref="#/components/schemas/Customer")
 *     ),
 *     @OA\Response(response=404, description="La ressource n'existe pas"),
 *     @OA\Response(response=401, description="Jeton d'authentification invalide"),
 *     @OA\Response(response=403, description="L'accès à cette page ne vous est pas autorisé")
 * )
 */
class DeleteCustomerController extends AbstractController
{
    public function __invoke(EntityManagerInterface $em, Customer $customer = null): Response
    {
        if ($customer === null) {
            return $this->json('Customer not found', 404);
        }

        if ($customer->getUser() !== $this->getUser()){
            return $this->json('No authorization', 403);

        }

        $em->remove($customer);
        $em->flush();
        return $this->json( '', 204);
    }
}
