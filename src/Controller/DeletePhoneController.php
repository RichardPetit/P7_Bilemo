<?php

namespace App\Controller;

use App\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/phones/{id}", name="phone_delete", methods={"DELETE"})
 */
class DeletePhoneController extends AbstractController
{

    public function __invoke(Phone $phone, EntityManagerInterface $em): Response
    {
        $em->remove($phone);
        $em->flush();
        return $this->json( '', 204);
    }
}
