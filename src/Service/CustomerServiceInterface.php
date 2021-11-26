<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\User;
use App\Exception\CustomerNotFoundException;

interface CustomerServiceInterface
{
    /**
     * @param int $customerId
     * @return Customer
     * @throws CustomerNotFoundException
     */
    public function getCustomerById(int $customerId): Customer;

    /**
     * @param int $userId
     * @param int $page
     * @return Customer[]
     */
    public function getCustomersForUser(int $userId, int $page): array;

    public function getItemsPerPage(): int;

    public function getTotalPages(int $userId): int;

    public function getTotalNumberOfCustomerForAUser(int $userId): int;

    public function createFromRequest(string $requestContent, User $user): Customer;

    public function getErrors(Customer $customer): \Countable;

    public function save(Customer $customer): Customer;

    public function delete(Customer $customer): Customer;

    public function belongsToUserId(Customer $customer, int $userId): bool;
}