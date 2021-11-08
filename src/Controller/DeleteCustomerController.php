<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/customers/{id}", name="customer_delete", methods={"DELETE"})
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
