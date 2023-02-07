<?php

namespace App\Repository;

use App\Entity\CompanySizeOptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CompanySizeOptions>
 *
 * @method CompanySizeOptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanySizeOptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanySizeOptions[]    findAll()
 * @method CompanySizeOptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanySizeOptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompanySizeOptions::class);
    }

    public function add(CompanySizeOptions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CompanySizeOptions $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CompanySizeOptions[] Returns an array of CompanySizeOptions objects
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

//    public function findOneBySomeField($value): ?CompanySizeOptions
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
