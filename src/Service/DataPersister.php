<?php

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Security;

class DataPersister
{
    protected $security;
     public function __construct(Security $security)
     {
         $this->security = $security;
     }


    public function flashAndPersistCustomer(Customer $customer,
                                            SerializerInterface $serializer,
                                            EntityManagerInterface $em,
                                            NormalizerInterface $normalizer,
                                            ValidatorInterface $validator)
    {
//        $customers = $this->em->getRepository('Repository/CustomerRepository')->findAll();
//
//        foreach ($customers as $customer){
//            $customer->set??? ;
//            $this->em->persist($customer);
//        }
//        $this->em->flush();
//    }
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

}