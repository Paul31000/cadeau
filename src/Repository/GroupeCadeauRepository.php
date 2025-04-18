<?php

namespace App\Repository;

use App\Entity\GroupeCadeau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupeCadeau>
 */
class GroupeCadeauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeCadeau::class);
    }

    /**
     * Homepage <=>groupe cadeaux lies a utilisateur
     */
    public function getGroupeCadeauxLies($userId): array
    {
        return $this->createQueryBuilder('gc')
                    ->join('gc.utilisateurConcernes','uc')
                    ->andWhere('uc.id = :id')
                    ->setParameter('id', $userId)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Page groupe cadeaux <=> Les utilisateurs et les cadeaux pour un groupe.
     */
    public function getUtilEtCadeauConcernes(GroupeCadeau $groupeCadeau)
    {
        return $this->createQueryBuilder('gc')
                    //->leftJoin('gc.listeCadeaux','lc')
                    //->leftJoin('lc.cadeaux','c')
                    //>leftJoin('gc.utilisateurConcernes','uc')
                    ->andWhere('gc.id = :id')
                    ->setParameter('id', $groupeCadeau->getId())
                    ->getQuery()
                    ->getResult();
    }

    //    /**
    //     * @return GroupeCadeau[] Returns an array of GroupeCadeau objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('g.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?GroupeCadeau
    //    {
    //        return $this->createQueryBuilder('g')
    //            ->andWhere('g.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
