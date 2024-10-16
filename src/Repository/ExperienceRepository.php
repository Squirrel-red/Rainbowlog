<?php

namespace App\Repository;

use App\Entity\Experience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Experience>
 */
class ExperienceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experience::class);
    }


    //  --> On prépare la requète pour la recherche des expériences  par title et/ou nearTown
    public function findExperience(?string $title, ?string $nearTown)
    {
        $qb = $this->createQueryBuilder('e');

        if ($title) {
            $qb->andWhere('e.title LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }

        if ($nearTown) {
            $qb->andWhere('e.nearTown LIKE :nearTown')
                ->setParameter('nearTown', '%' . $nearTown . '%');
        }

        return $qb->orderBy('e.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }


    //    /**  --> Exemples des requètes pour les autres cas

    //     * @return Experience[] Returns an array of Experience objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Experience
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


}
