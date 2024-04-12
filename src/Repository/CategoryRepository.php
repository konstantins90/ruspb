<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAllWithPosts()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.posts', 'p')
            ->addSelect('p')
            ->andWhere('c.posts IS NOT EMPTY')
            ->getQuery()
            ->getResult();
    }

    public function findForHome($limit = 5)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.posts', 'p')
            // ->addSelect('COUNT(p.id) AS post_count')
            ->groupBy('c.id, c.name')
            ->having('COUNT(p.id) > 0')
            ->orderBy('COUNT(p.id)', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
