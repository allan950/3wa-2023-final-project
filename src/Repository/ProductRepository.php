<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getOne(int $id): array|null {
        return $this->createQueryBuilder('p')
        ->select(array("p.id", "p.name", "p.description", "p.price", "p.code", "p.urlimg", "ca.label AS category", "an.label AS anime"))
        ->leftJoin("p.category", "ca")
        ->leftJoin("p.anime", "an")
        ->where("p.id = :id")
        ->setParameter("id", $id)
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult()
    ;
    }

    public function getAllWithCategory(): array {
        return $this->createQueryBuilder('p')
            ->select(array("p.id", "p.name", "p.description", "p.price", "p.code", "p.urlimg", "ca.label AS category", "an.label AS anime"))
            ->leftJoin("p.category", "ca")
            ->leftJoin("p.anime", "an")
            ->getQuery()
            ->getResult()
        ;
    }

    public function queryFilteredList(array $load): array
    {
        $builder = $this->createQueryBuilder('p')
            ->select(array("p.id", "p.name", "p.description", "p.price", "p.code", "p.urlimg", "ca.label as category", "an.label as anime"))
            ->leftJoin("p.category", "ca")
            ->leftJoin("p.anime", "an")
            ->where("1 = 1")
        ;

        if (!empty($load['id'])) { $builder->andWhere("p.id = :id")->setParameter("id", $load['id']); }
        if (!empty($load['name'])) { $builder->andWhere("p.name LIKE :name")->setParameter("name", '%'.$load['name'].'%'); }
        if (!empty($load['price'])) { $builder->andWhere("p.price = :price")->setParameter("price", $load['price']); }
        if (!empty($load['anime']) && $load['anime'] != "all") { $builder->andWhere("an.label = :anime")->setParameter("anime", $load['anime']); }
        if (!empty($load['category']) && $load['category'] != "all") { $builder->andWhere("ca.label = :category")->setParameter("category", $load['category']); }

        return $builder->getQuery()->getResult();
    }

    public function filterProducts($params) {

        $builder = $this->createQueryBuilder('p')
            ->select(array("p.id", "p.name", "p.description", "p.price", "p.code", "p.urlimg", "ca.label AS category", "an.label AS anime"))
            ->leftJoin("p.category", "ca")
            ->leftJoin("p.anime", "an")
            ->where("1 = 1")
        ;

        if (!empty($params['anime'])) {
            $anime = str_replace("-", " ", $params['anime']);
            $builder->andWhere("an.label IN (:anime)")
               ->setParameter("anime", $anime);
           }

        if (!empty($params['category'])) {
         $builder->andWhere("ca.label IN (:category)")
            ->setParameter("category", $params['category']);
        }

        if (!empty($params["minPrice"])) {
            $builder->andWhere("p.price >= :minPrice")
            ->setParameter("minPrice", $params["minPrice"]);
        }

        if (!empty($params["maxPrice"])) {
            $builder->andWhere("p.price <= :maxPrice")
            ->setParameter("maxPrice", $params["maxPrice"]);
        }

        return $builder->getQuery()->getResult();
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
