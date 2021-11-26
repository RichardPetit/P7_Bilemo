<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;

class UserService implements UserServiceInterface
{

    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getUserById(int $id): User
    {
        $user = $this->userRepository->find($id);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function isExpectedUser(int $expectedUserId, int $actualUserId): bool
    {
        return $expectedUserId === $actualUserId;
    }
}