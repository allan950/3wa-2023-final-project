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

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    public function update(array $params) 
    {
        $builder = $this->createQueryBuilder('u')
            ->update('App\Entity\User', 'u')
            ->where('u.id = ?0')
            ->setParameter(0, $params['user_id'])
            ->set('u.firstName', '?1')
            ->set('u.lastName', '?2')
            ->set('u.address', '?3')
            ->set('u.zipcode', '?4')
            ->set('u.city', '?5')
            ->setParameter(1, $params['first_name'])
            ->setParameter(2, $params['last_name'])
            ->setParameter(3, $params['address'])
            ->setParameter(4, $params['zipcode'])
            ->setParameter(5, $params['city'])
            ->getQuery()
            ;

        return $builder->execute();
    }

    public function queryFilteredList(array $load) {
        $builder = $this->createQueryBuilder('u')
            ->select(array("u.id", "u.email", "u.roles", "u.lastName", "u.firstName"))
            ->where("1 = 1")
        ;

        if ($load['id']) { $builder->andWhere("u.id = :id")->setParameter("id", $load['id']); }
        if ($load['email']) { $builder->andWhere("u.email = :email")->setParameter("email", $load['email']); }
        /* if ($load['roles']) { 
            if ($load['roles'] == "user") {
                $load['roles'] = "ROLE_USER";
            } else if ($load['roles'] == "admin") {
                $load['roles'] = "ROLE_ADMIN";
            } else {
                $load['roles'] = "ROLE_USER";
            }

            //dd($load['roles']);

            $builder->andWhere("JSON_CONTAINS(u.roles, :roles, '$')")->setParameter("roles", $load['roles']);
        } */
        if ($load['last_name']) { $builder->andWhere("u.lastName = :last_name")->setParameter("last_name", $load['last_name']); }
        if ($load['first_name']) { $builder->andWhere("u.firstName = :first_name")->setParameter("first_name", $load['first_name']); }

        return $builder->getQuery()->getResult();
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
}
