<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Phone;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Service\DataPersister;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CustomerController extends AbstractController
{
    /**
     * @Route("/users/{user}/customers", name="customer", methods={"GET"})
     * @OA\Get(
     *     path="/users/{user}/customers",
     *     @OA\Response(
     *          response="200",
     *          description="Liste des clients",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *     ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton d'authentification invalide"),
     *     @OA\Response(response=403, description="L'accès à cette page ne vous est pas autorisé")
     * )
     */
    public function list(CustomerRepository $customerRepository,
                         NormalizerInterface $normalizer,
                         Request $request,
                         User $user = null): Response
    {
        if ($user === null) {
            return $this->json('User not found', 404);
        }
        if ($user !== $this->getUser()) {
            return $this->json('No authorization', 403);
        }

        $page = $request->get('page') !== null ? (int) $request->get('page') : 1;

        $customers = $customerRepository->getUsersByCreationDate($page);
//        $customers = $customerRepository->findBy(['user' => $this->getUser()]);
        $response = $normalizer->normalize($customers, 'array', [AbstractNormalizer::GROUPS => ['customerList', Phone::GROUP_DETAIL]]);

        return $this->json( $response
//            ,[
//            'customers' => $customers,
//            'nbPages'   => $customerRepository->getNbOfPages(),
//            'currentPage' => $page,
//            'url' => 'users/{user}/customers'
//        ]
        );

    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @Route("/customers", name="customer_create", methods={"POST"} )
     * @OA\Post(
     *     path="/customers",
     *     security={"bearer"},
     *     @OA\Response(
     *          response="201",
     *          description="Création d'un client",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Customer"))
     *      ),
     *     @OA\Response(response=404, description="La ressource n'existe pas"),
     *     @OA\Response(response=401, description="Jeton d'authentification invalide"),
     *     @OA\Response(response=403, description="L'accès à cette page ne vous est pas autorisé")
     * )
     */
    public function create(Request $request,
                           SerializerInterface $serializer,
                           EntityManagerInterface $em,
                           NormalizerInterface $normalizer,
                           DataPersister $dataPersister,
                           ValidatorInterface $validator): JsonResponse
    {
        $data = $request->getContent();

        try {
            $customer = $serializer->deserialize($data, Customer::class, 'json');
            $customer->setUser($this->getUser());

            $error = $validator->validate($customer);
            if (count($error) > 0) {
                return $this->json($error, 400);
            }
            $em->persist($customer);
            $em->flush();

            $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);
            return $this->json($response, 201);

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

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
    public function delete(EntityManagerInterface $em, Customer $customer = null): Response
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

    /**
     * @Route("/users/{user}/customers/{customer}", name="customer_detail", methods={"GET"})
     * @OA\Get (
     *     path="/users/{user}/customers/{customer}",
     *     security={"bearer"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID du client",
     *         required=true,
     *         @OA\Schema(type="integer")
     *      ),
     *     @OA\Response(
     *          response="200",
     *          description="Détails d'un client",
     *          @OA\JsonContent(ref="#/components/schemas/Customer")
     *     ),
     *     @OA\Response(response=404, description="Le client n'existe pas"),
     *     @OA\Response(response=401, description="Jeton d'authentification invalide"),
     *     @OA\Response(response=403, description="L'accès à cette page ne vous est pas autorisé")
     * )
     */
    public function details(NormalizerInterface $normalizer, User $user = null, Customer $customer = null): Response
    {
        if ($user === null) {
            return $this->json('User not found', 404);
        }

        if ($user !== $this->getUser()){
            return $this->json('No authorization', 403);
        }

        if ($customer === null) {
            return $this->json('Customer not found', 404);
        }

        if ($customer->getUser() !== $this->getUser()){
            return $this->json('No authorization', 403);
        }

        $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);

        return $this->json( $response);
    }

}
