<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reclamation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reclamation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reclamation[]    findAll()
 * @method Reclamation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    // /**
    //  * @return Reclamation[] Returns an array of Reclamation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reclamation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function OrderByPrice()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\Reclamation m order by m.datereclamation,m.nom,m.prenom,m.email,m.commentaire,m.typereclamation ASC');
        return $query->getResult();

    }

    public function getART()
    {

        $qb = $this->createQueryBuilder('v')
            ->select('COUNT(v.typereclamation) AS rec, SUBSTRING(v.typereclamation, 1, 100000) AS typereclamation')
            ->groupBy('typereclamation');
        return $qb->getQuery()
            ->getResult();

    }


    public function Filter($date)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\reclamation m where m.typereclamation =:typereclamation')
            ->setParameter('typereclamation', $date);
        return $query->getResult();
    }

    public function findType($typereclamation){
        return $this->createQueryBuilder('Reclamation')
            ->where('Reclamation.typereclamation LIKE :typereclamation')
            ->setParameter('typereclamation', '%'.$typereclamation.'%')
            ->getQuery()
            ->getResult();
    }



    public function find_Nb_Rec_Par_Status($type)
    {

        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT DISTINCT  count(r.numero) FROM   App\Entity\Reclamation r  where r.typereclamation = :typereclamation   '
        );
        $query->setParameter('typereclamation', $type);
        return $query->getResult();
    }

}