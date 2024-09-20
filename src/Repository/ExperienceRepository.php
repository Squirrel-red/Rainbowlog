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

    //    /**
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

    public function findExperience(?string $keyword, ?string $nearTown)
    {
        $qb = $this->createQueryBuilder('e');

        if ($keyword) {
            $qb->andWhere('e.title LIKE :keyword OR e.description LIKE :keyword OR e.nearTown LIKE :keyword')
                ->setParameter('keyword', '%' . $keyword . '%');
        }

        if ($nearTown) {
            $qb->andWhere('e.nearTown LIKE :nearTown')
                ->setParameter('', '%' . $nearTown . '%');
        }

        return $qb->orderBy('e.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
