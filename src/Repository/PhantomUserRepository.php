<?php

namespace App\Repository;

use App\Entity\PhantomUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PhantomUsers>
 */
class PhantomUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PhantomUser::class);
    }

    //    /**
    //     * @return PhantomUsers[] Returns an array of PhantomUsers objects
    //     */
        public function findPhantomUserMatch(string $mailPhantom): ?PhantomUser
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.mail = :val')
                ->setParameter('val', $mailPhantom)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        }

    //    public function findOneBySomeField($value): ?PhantomUsers
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
