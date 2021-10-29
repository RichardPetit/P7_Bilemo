<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PhoneController extends AbstractController
{
    /**
     * @Route("/phone", name="phone", methods={"GET"})
     */
    public function index(PhoneRepository $phoneRepository): Response
    {
        return $this->json( $phoneRepository->findAll(),200, []);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @Route("/phone", name="phone_create", methods={"POST"})
     */
    public function createAction(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
    ValidatorInterface $validator)
    {
        $data = $request->getContent();
        try {
            $phone = $serializer->deserialize($data,Phone::class, 'json');

            $error = $validator->validate($phone);
            if (count($error) > 0){
                return $this->json($error,400);
            }
            $em->persist($phone);
            $em->flush();


            return $this->json($phone,201, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

//    /**
//     * @Route("/phone", name="phone")
//     */
//    public function __invoke(Phone $phone): JsonResponse
//    {
//        $data = $this->get('jms_serializer')->serialize($phone, 'json',
//            SerializationContext::create()->setGroups(array('phoneDetail')));
//
//        $response = new JsonResponse($data);
//        $response->headers->set('Content-Type', 'application/json');
//
//        return $response;
////        return $this->json('test phone');
//    }
//    public function __invoke(PhoneRepository $phoneRepository): JsonResponse
//    {
//        return $phoneRepository->findAll();
//    }


}
