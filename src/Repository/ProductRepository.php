<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function getAllWithCategory(): array {
        return $this->createQueryBuilder('p')
            ->select(array("p.id", "p.name", "p.description", "p.price", "p.code", "p.urlimg", "ca.label AS category"))
            ->leftJoin("p.category", "ca")
            ->getQuery()
            ->getResult()
        ;
    }

    public function queryFilteredList(int | null $id, string | null $name, float | null $price, string | null $label): array
    {
        $builder = $this->createQueryBuilder('p')
            ->select(array("p.id", "p.name", "p.description", "p.price", "p.code", "p.urlimg", "ca.label AS category"))
            ->leftJoin("p.category", "ca")
            ->where("1 = 1")
        ;

        if (!empty($id)) { $builder->andWhere("p.id = :id")->setParamter("id", $id); }
        if (!empty($name)) { $builder->andWhere("p.name = :name")->setParamter("name", $name); }
        if (!empty($price)) { $builder->andWhere("p.price = :price")->setParamter("price", $price); }
        if (!empty($label)) { $builder->andWhere("p.label = :label")->setParamter("label", $label); }

        return $builder->getQuery()->getResult();
    }

    public function filterProducts($params) {

        $builder = $this->createQueryBuilder('p')
            ->select(array("p.id", "p.name", "p.description", "p.price", "p.code", "p.urlimg", "ca.label AS category"))
            ->leftJoin("p.category", "ca")
            ->where("1 = 1")
        ;

        if (!empty($params->all('category'))) {
         $builder->andWhere("ca.label IN (:category)")
            ->setParameter("category", $params->all('category'));
        }
        if (!empty($params->get('min-price'))) {
            $builder->andWhere("p.price >= :minPrice")
            ->setParameter("minPrice", $params->get('min-price'));
        }

        if (!empty($params->get('max-price'))) {
            $builder->andWhere("p.price <= :maxPrice")
            ->setParameter("maxPrice", $params->get('max-price'));
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
