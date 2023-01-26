<?php

namespace App\Repository;

use App\Entity\CompanyRevenueOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanyRevenueOptions>
 *
 * @method CompanyRevenueOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyRevenueOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyRevenueOptions[]    findAll()
 * @method CompanyRevenueOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRevenueOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanyRevenueOptions::class);
    }

    public function add(CompanyRevenueOptions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompanyRevenueOptions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CompanyRevenueOptions[] Returns an array of CompanyRevenueOptions objects
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

//    public function findOneBySomeField($value): ?CompanyRevenueOptions
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
