<?php

namespace App\Repository;

use App\Entity\ListeCadeau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeCadeau>
 */
class ListeCadeauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeCadeau::class);
    }

     /**
     * Homepage <=>groupe cadeaux lies a utilisateur
     */
    public function findListForCurrentUser($userId, $groupeCadeauId): ?ListeCadeau
    {
        $listeCadeauArray= $this->createQueryBuilder('lc')
                                ->join('lc.utilisateur','u')
                                ->join('lc.groupeCadeau','gc')
                                ->andWhere('u.id = :idUtil')
                                ->andWhere('gc.id = :idGrouCad')
                                ->setParameter('idUtil', $userId)
                                ->setParameter('idGrouCad', $groupeCadeauId)
                                ->getQuery()
                                ->getResult();
        
        if(isset($listeCadeauArray[0])){
            return $listeCadeauArray[0];
        }else{
            return null; 
        }

        
    }
    //    /**
    //     * @return ListeCadeau[] Returns an array of ListeCadeau objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ListeCadeau
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
