<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
      return $this->json( $articleRepository->findAll(),200, []);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/article", name="article_create", methods={"POST"} )
     */
    public function createAction(Request $request,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $em,
                                 ValidatorInterface $validator)
    {
        $jsonRecu = $request->getContent();

        try {
            $article = $serializer->deserialize($jsonRecu, Article::class, 'json');

            $error = $validator->validate($article);
            if (count($error) > 0) {
                return $this->json($error, 400);
            }

            $em->persist($article);
            $em->flush();

            return $this->json($article, 201, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

        //        $data = $request->getContent();
//        $article = $this->get('serializer')
//            ->deserialize($data, 'Bundle\Entity\Article', 'json');
//        dd($article);
    }

}
