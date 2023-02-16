<?php

namespace App\Repository;

use App\Entity\Juez;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Juez>
 *
 * @method Juez|null find($id, $lockMode = null, $lockVersion = null)
 * @method Juez|null findOneBy(array $criteria, array $orderBy = null)
 * @method Juez[]    findAll()
 * @method Juez[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JuezRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Juez::class);
    }

    public function save(Juez $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Juez $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Juez[] Returns an array of Juez objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Juez
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Find all with DQL
    public function findAllJuecesWithDQL(): array
    {
        return $this->getEntityManager()->createQuery('select j,p from App:Juez j JOIN j.persona p')->getResult();
    }

    // find all with query builder
    // public function findAllJuecesWithQB(): array
    // {
    //     $query = $this->createQueryBuilder('j')
    //         ->innerJoin('j.id_persona', 'p')
    //         ->getQuery();
    // }

    public function findAllJuecesWithNativeSQL()
    {
        $sql = "select j.id as juezID,j.num_profesion,p.npmbre.p.dni,p.email from Juez j inner join Persona p on j.persona_id = p.id";

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('App:Juez', 'j');
        $rsm->addJoinedEntityFromClassMetadata('App:Persona', 'p', 'j', 'persona', array('id' => 'persona_id'));
        return $rsm;
    }
}
