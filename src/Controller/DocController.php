<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doc", name="doc")
 */
class DocController extends AbstractController
{

    public function __invoke(): Response
    {
        return $this->render('doc/index.html.twig', [
            'controller_name' => 'DocController',
        ]);
    }
}
