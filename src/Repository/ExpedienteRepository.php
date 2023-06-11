<?php

namespace App\Repository;

use App\Entity\Expediente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Expediente>
 *
 * @method Expediente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expediente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expediente[]    findAll()
 * @method Expediente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpedienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $emi)
    {
        parent::__construct($registry, Expediente::class);
    }

    public function save(Expediente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Expediente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Expediente[] Returns an array of Expediente objects
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

//    public function findOneBySomeField($value): ?Expediente
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    // function to get the greater expediente ID
    public function greaterId()
    {
        $query = $this->emi->createQuery(
            'SELECT e FROM App\Entity\Expediente e ORDER BY e.id DESC'
            )
            ->setMaxResults(1); // sirve para obtener el primer resultado (LIMIT 1)
            // ->setFirstResult(14);
        $exp = $query->getResult();
        return $exp;
    }
}
