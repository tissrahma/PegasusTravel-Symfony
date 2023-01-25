<?php

namespace App\Repository;

use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReclamationBackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamation::class);
    }

    public function findevent($valueemail,$order){
        $em = $this->getEntityManager();
        if($order=='DESC') {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Reclamation r  where r.nom like :nom order by r.numero DESC '
            );
            $query->setParameter('nom', $valueemail . '%');
        }
        else {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Reclamation r   where r.nom like :nom  order by r.numero ASC '
            );
            $query->setParameter('nom', $valueemail . '%');
        }



        return $query->getResult();
}
    public function FindRecWithNAME($Name){
        return $this->createQueryBuilder('reclamation')
            ->where('reclamation.nom LIKE :nom')
            ->setParameter('nom', '%'.$Name.'%')
            ->getQuery()
            ->getResult();
    }

    public function findTeamwithNumber($num){
        return $this->createQueryBuilder('Reclamation')
            ->where('Reclamation.nom LIKE :nom')
            ->setParameter('nom', '%'.$num.'%')
            ->getQuery()
            ->getResult();
    }

    public function Filte($date)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('select m from App\Entity\reclamation m where m.datereclamation <:datereclamation')
            ->setParameter('datereclamation', $date);
        return $query->getResult();
    }
    public function DescReclamationSearch($order){
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT e FROM App\Entity\Reclamation e order by e.datereclamation DESC ');
        return $query->getResult();
    }

    public function AscReclamationSearch($order){
        $em = $this->getEntityManager();

        $query = $em->createQuery('SELECT e FROM App\Entity\Reclamation e order by e.datereclamation ASC  ');
        return $query->getResult();
    }

}
