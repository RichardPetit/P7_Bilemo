<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Phone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phone[]    findAll()
 * @method Phone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhoneRepository extends ServiceEntityRepository
{
    public const NB_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function getPhones(int $page = 1 , int $nbResults = self::NB_PER_PAGE)
    {
        $offset = ($page -1) * $nbResults;
        return $this->createQueryBuilder('p')
            ->getQuery()
            ->setMaxResults($nbResults)
            ->setFirstResult($offset)
            ->getResult()
            ;
    }

    public function getTotalNumberOfPhones() : int
    {
        return $this->createQueryBuilder('p')
            ->select('count(p.id)')
            ->getQuery()->getSingleScalarResult();
    }

    public function getItemsPerPage() : int
    {
        return self::NB_PER_PAGE;
    }

    public function getTotalPages() : int
    {
        $totalCount = $this->getTotalNumberOfPhones();
        if ($totalCount <= self::NB_PER_PAGE) {
            return 1;
        }
        return $totalCount % self::NB_PER_PAGE === 0 ? $totalCount / self::NB_PER_PAGE : ceil($totalCount / self::NB_PER_PAGE);

    }


    // /**
    //  * @return Phone[] Returns an array of Phone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Phone
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
