<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Phone;
use App\Entity\User;
use App\Exception\CustomerNotFoundException;
use App\Exception\UserNotFoundException;
use App\Repository\CustomerRepository;
use App\Service\CustomerServiceInterface;
use App\Service\DataPersister;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use OpenApi\Annotations as OA;


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
    public function list(CustomerServiceInterface $customerService,
                         UserServiceInterface $userService,
                         NormalizerInterface $normalizer,
                         Request $request,
                         int $user): Response
    {
        try {
            $userEntity = $userService->getUserById($user);
        }catch(UserNotFoundException $exception) {
            return $this->json('User not found', Response::HTTP_NOT_FOUND);
        }

        $userId = $this->getUser()->getId();

        if (!$userService->isExpectedUser($userEntity->getId(), $userId)) {
            return $this->json('No authorization', Response::HTTP_FORBIDDEN);
        }

        $page = $request->get('page') !== null ? (int) $request->get('page') : 1;

        $customers = $customerService->getCustomersForUser($userId, $page);
        $customersResponse = $normalizer->normalize($customers, 'array', [AbstractNormalizer::GROUPS => ['customerList', Phone::GROUP_DETAIL]]);
        $response = [
            'items' => $customersResponse,
            'page' => $page,
            'itemsPerPage' => $customerService->getItemsPerPage(),
            'totalPages' => $customerService->getTotalPages($userId),
            'totalItems' => $customerService->getTotalNumberOfCustomerForAUser($userId)
        ];

        return $this->json( $response );

    }

    /**
     * @param Request $request
     * @param CustomerServiceInterface $customerService
     * @param NormalizerInterface $normalizer
     * @return JsonResponse
     * @throws ExceptionInterface
     * @Route("/users/{userId}/customers", name="customer_create", methods={"POST"} )
     * @OA\Post(
     *     path="/users/{userId}/customers",
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
    public function create(
        int $userId,
        Request $request,
        UserServiceInterface $userService,
        CustomerServiceInterface $customerService,
        NormalizerInterface $normalizer
    ): JsonResponse {

        try {
            $user = $userService->getUserById($userId);
        } catch (UserNotFoundException $exception) {
            return $this->json('User not found', Response::HTTP_NOT_FOUND);
        }

        if(!$userService->isExpectedUser($this->getUser()->getId(), $userId)) {
            return $this->json('No authorization', Response::HTTP_FORBIDDEN);
        }

        $data = $request->getContent();

        try {
            $customer = $customerService->createFromRequest($data, $user);

            $errors = $customerService->getErrors($customer);
            if (count($errors) > 0) {
                return $this->json($errors, Response::HTTP_BAD_REQUEST);
            }

            $customerService->save($customer);

            $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);
            return $this->json($response, Response::HTTP_CREATED);

        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/users/{userId}/customers/{customerId}", name="customer_delete", methods={"DELETE"})
     * @OA\Delete(
     *     path="/users/{userId}/customers/{customerId}",
     *     security={"bearer"},
     *     @OA\Parameter(
     *          name="userId",
     *          in="path",
     *          description="ID du client de l'API",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *     @OA\Parameter(
     *          name="customerId",
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
    public function delete(
        int $userId,
        int $customerId,
        CustomerServiceInterface $customerService,
        UserServiceInterface $userService
    ): Response {
        try {
            $user = $userService->getUserById($userId);
        } catch (UserNotFoundException $exception) {
            return $this->json('User not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $customer = $customerService->getCustomerById($customerId);
        } catch (CustomerNotFoundException $exception) {
            return $this->json('Customer not found', Response::HTTP_NOT_FOUND);
        }

        $userLoggedId = $this->getUser()->getId();
        if(!$userService->isExpectedUser($userLoggedId, $userId)) {
            return $this->json('No authorization', Response::HTTP_FORBIDDEN);
        }

        if(!$customerService->belongsToUserId($customer, $userLoggedId)) {
            return $this->json('No authorization', Response::HTTP_FORBIDDEN);
        }

        $customerService->delete($customer);
        return $this->json( '', Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/users/{userId}/customers/{customerId}", name="customer_detail", methods={"GET"})
     * @OA\Get (
     *     path="/users/{userId}/customers/{customerId}",
     *     security={"bearer"},
     *     @OA\Parameter(
     *         name="userId",
     *         in="path",
     *         description="ID du client de l'API",
     *         required=true,
     *         @OA\Schema(type="integer")
     *      ),
     *     @OA\Parameter(
     *         name="customerId",
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
    public function details(
        int $userId,
        int $customerId,
        UserServiceInterface $userService,
        CustomerServiceInterface $customerService,
        NormalizerInterface $normalizer
    ): Response {
        try {
            $user = $userService->getUserById($userId);
        } catch (UserNotFoundException $exception) {
            return $this->json('User not found', Response::HTTP_NOT_FOUND);
        }

        try {
            $customer = $customerService->getCustomerById($customerId);
        } catch (CustomerNotFoundException $exception) {
            return $this->json('Customer not found', Response::HTTP_NOT_FOUND);
        }

        $userLoggedId = $this->getUser()->getId();
        if(!$userService->isExpectedUser($userLoggedId, $userId)) {
            return $this->json('No authorization', Response::HTTP_FORBIDDEN);
        }

        if(!$customerService->belongsToUserId($customer, $userLoggedId)) {
            return $this->json('No authorization', Response::HTTP_FORBIDDEN);
        }

        $response = $normalizer->normalize($customer, 'array', [AbstractNormalizer::GROUPS => ['customerDetail', Phone::GROUP_DETAIL]]);

        return $this->json($response);
    }

}
