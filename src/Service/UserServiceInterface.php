<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;

interface UserServiceInterface
{
    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getUserById(int $id): User;

    public function isExpectedUser(int $expectedUserId, int $actualUserId): bool;
}