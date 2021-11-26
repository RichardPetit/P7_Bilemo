<?php

namespace App\Service;

use App\Entity\Phone;
use App\Exception\PhoneNotFoundException;
use App\Repository\PhoneRepository;
use function PHPUnit\Framework\throwException;

class PhoneService implements PhoneServiceInterface
{

    private PhoneRepository $phoneRepository;

    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    /**
     * @param int $phoneId
     * @return Phone
     * @throws PhoneNotFoundException
     */
    public function getPhoneById(int $phoneId): Phone
    {
        $phone = $this->phoneRepository->find($phoneId);
        if ($phone === null){
            throw new PhoneNotFoundException();
        }
        return $phone;

    }

    public function getPhones(int $page): array
    {
        return $this->phoneRepository->getPhones($page);
    }

    public function getItemsPerPage(): int
    {
        return $this->phoneRepository->getItemsPerPage();
    }

    public function getTotalPages(): int
    {
        return $this->phoneRepository->getTotalPages();
    }

    public function getTotalNumberOfPhones(): int
    {
        return $this->phoneRepository->getTotalNumberOfPhones();
    }
}