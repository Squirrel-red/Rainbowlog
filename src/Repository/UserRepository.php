<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 * 
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
        // --> On créé la méthode pour trouver les users

        public function findUsers(?int $userId = null) {
            $em = $this->getEntityManager();
            $qb = $em->createQueryBuilder();
            
            
            $qb ->select('u')
                    ->from('App\Entity\User', 'u')
                    ->where('u.roles LIKE :role')
                    ->setParameter('role', '%"ROLE_USER"%');
            
            // Filtre sur ID renseigné
                if ($userId !== null) {
                $qb->andWhere('u.id = :userId')
                    ->setParameter('userId', $userId);
                }
            
                $query = $qb->getQuery();
                return $query->getResult();
            
        }

        // --> On créé la méthode pour l'anonymisation d'un user
        public function hideUser(User $user): void
        {
            $randomString = bin2hex(random_bytes(5)); // On créé la chène de caractères aléatoires
            $user->setPseudo('Anonyme');
            $user->setEmail('anonyme_'. $randomString . '@domain.com');
            $user->setIsBlocked(true); // user sera  blockè
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }


        // --> On créé la méthode pour bloquer un user ()
        public function blockUser(User $user): void
        {
            $user->setIsBlocked(true);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }


        // --> On créé la méthode pour débloquer un user ()
        public function unblockUser(User $user): void
        {
            $user->setIsBlocked(false);
            $this->getEntityManager()->persist($user);
            $this->getEntityManager()->flush();
        }


        // SELECT AVG(rating) AS AVG, COUNT(rating) AS COUNT, user_id AS user FROM evaluation  GROUP BY user_id

        //  // --> On créé une methode pour avoir la moyenne de l'évaluation 
        //  public function getAverageRating(User $user): ?float
        // {
        //     $em = $this->getEntityManager('e');
        //     // $qb = $em->createQueryBuilder();

        //     $qb = $this->createQueryBuilder('u')
        //     ->select('AVG(e.rating)as avgRating)
        //     ->count('rating')
        //     ->where('u.id = :user')
        //     ->setParameter('user', $user->getId())
        //     ->getQuery();

        //     // $query = $qb->getQuery();
        //     //     return $query->getResult();
        //         return $qb->getSingleScalarResult();           
        // }


        // --> On créé une methode pour mettre compteur des nouveaux messages à 0
        public function updateNewMessages(User $user) 
        {
            $user->setNewMessages(0);
        } 

        

        // --> find user by pseudo
        public function findUserByPseudo(User $user)
        {
            return $this->createQueryBuilder('u')
                ->andWhere('u.pseudo LIKE :pseudo')
                ->setParameter('pseudo', '%"Anonyme"%');
                $user ->getQuery();
                return $user->getResult();
        }
    



}
