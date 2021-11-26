<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\User;
use App\Exception\CustomerNotFoundException;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CustomerService implements CustomerServiceInterface
{
    private CustomerRepository $customerRepository;

    private SerializerInterface $serializer;

    private ValidatorInterface $validator;

    private EntityManagerInterface $em;

    /**
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     */
    public function __construct(CustomerRepository $customerRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em)
    {
        $this->customerRepository = $customerRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->em = $em;
    }

    public function getCustomersForUser(int $userId, int $page): array
    {
        return $this->customerRepository->getCustomersForUser($userId, $page);
    }

    public function getItemsPerPage(): int
    {
        return $this->customerRepository->getItemsPerPage();
    }

    public function getTotalPages(int $userId): int
    {
        return $this->customerRepository->getTotalPages($userId);
    }

    public function getTotalNumberOfCustomerForAUser(int $userId): int
    {
        return $this->customerRepository->getTotalNumberOfCustomerForAUser($userId);
    }

    public function createFromRequest(string $requestContent, User $user): Customer
    {
        $customer = $this->serializer->deserialize($requestContent, Customer::class, 'json');
        $customer->setUser($user);

        return $customer;
    }

    public function getErrors(Customer $customer): \Countable
    {
        return $this->validator->validate($customer);
    }

    public function save(Customer $customer): Customer
    {
        $this->em->persist($customer);
        $this->em->flush();

        return $customer;
    }

    /**
     * @param int $customerId
     * @return Customer
     * @throws CustomerNotFoundException
     */
    public function getCustomerById(int $customerId): Customer
    {
        $customer = $this->customerRepository->find($customerId);

        if($customer === null) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }

    public function delete(Customer $customer): Customer
    {
        $this->em->remove($customer);
        $this->em->flush();

        return $customer;
    }

    public function belongsToUserId(Customer $customer, int $userId): bool
    {
        return $customer->getUser()->getId() === $userId;
    }
}