<?php

namespace App\Repository;

use App\Entity\TpProcedimiento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TpProcedimiento>
 *
 * @method TpProcedimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method TpProcedimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method TpProcedimiento[]    findAll()
 * @method TpProcedimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TpProcedimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TpProcedimiento::class);
    }

    public function save(TpProcedimiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TpProcedimiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TpProcedimiento[] Returns an array of TpProcedimiento objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TpProcedimiento
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
