<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
//    /**
//     * @Route("/article", name="article")
//     */
//    public function index(): Response
//    {
//        return $this->render('article/index.html.twig', [
//            'controller_name' => 'ArticleController',
//        ]);
//    }

    /**
    * @Route("/articles", name="article_create", methods={"POST"} )
    */
    public function createAction(Request $request)
    {
        $data = $request->getContent();
        $article = $this->get('serializer')
            ->deserialize($data, 'Bundle\Entity\Article', 'json');
        dd($article);
    }

}
