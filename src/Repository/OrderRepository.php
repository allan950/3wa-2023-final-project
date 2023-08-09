<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function queryFilteredList(array $load) {
        $builder = $this->createQueryBuilder('o')
            ->select(array("o.id", "client.email", "o.orderDate", "o.totalPriceHt", "o.totalPriceTtc"))
            ->leftJoin("o.client", "client")
            ->where("1 = 1")
        ;

        if (!empty($load['id'])) { $builder->andWhere("o.id = :id")->setParameter("id", $load['id']); }
        if (!empty($load['client'])) { $builder->andWhere("client.email = :email")->setParameter("email", $load['client']); }
        /* if (!empty($load['order_date'])) { $builder->andWhere("DATE_FORMAT(o.orderDate, '%Y-%m-%d') = :order_date")->setParameter("order_date", $load['order_date']); } */
        if (!empty($load['total_price_ht'])) { $builder->andWhere("o.totalPriceHt = :total_price_ht")->setParameter("total_price_ht", $load['total_price_ht']); }
        if (!empty($load['total_price_ttc'])) { $builder->andWhere("o.totalPriceTtc = :total_price_ttc")->setParameter("total_price_ttc", $load['total_price_ttc']); }

        return $builder->getQuery()->getResult();
    }

    public function insert(array $payload) {
        $builder = $this->createNativeNamedQuery("");
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Order
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
