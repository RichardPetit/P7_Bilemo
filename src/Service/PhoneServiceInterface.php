<?php

namespace App\Service;

use App\Entity\Phone;
use App\Exception\PhoneNotFoundException;

interface PhoneServiceInterface
{
    /**
     * @param int $phoneId
     * @return Phone
     * @throws PhoneNotFoundException
     */
    public function getPhoneById( int $phoneId) : Phone;

    /**
     * @param int $page
     * @return Phone[]
     */
    public function getPhones( int $page) : array;

    public function getItemsPerPage() : int;

    public function getTotalPages() : int;

    public function getTotalNumberOfPhones() : int;

}