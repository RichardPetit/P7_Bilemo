<?php

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public const NB_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function getUsersByCreationDate(int $page = 1 , int $nbResults = self::NB_PER_PAGE)
    {
        $offset = ($page -1) * $nbResults;
        return $this->createQueryBuilder('u')
//            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->setMaxResults($nbResults)
            ->setFirstResult($offset)
            ->getResult()
            ;
    }

    public function getTotalNumberOfUsersForACustomer(User $user) : int
    {
        return $this->createQueryBuilder('c')
            ->select('count(c.id)')
            ->where('c.user = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()->getSingleScalarResult();
    }

    public function getNbOfPages(User $user) : int
    {
        $totalCount = $this->getTotalNumberOfUsersForACustomer($user);
        if ($totalCount <= self::NB_PER_PAGE) {
            return 1;
        }
        return $totalCount % self::NB_PER_PAGE === 0 ? $totalCount / self::NB_PER_PAGE : ceil($totalCount / self::NB_PER_PAGE);
    }

    // /**
    //  * @return Customer[] Returns an array of Customer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
