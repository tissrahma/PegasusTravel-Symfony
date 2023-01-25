<?php

namespace App\Repository;
use App\Entity\Reponsereclamation;
use App\Entity\Reclamation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
class ReponsereclamationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reponsereclamation::class);
    }



    public function findevent($valueemail, $order)
    {
        $em = $this->getEntityManager();
        if ($order == 'DESC') {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Reponsereclamation r   where r.reponse like :nom order by r.idrep DESC '
            );
            $query->setParameter('nom', $valueemail . '%');
        } else {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Reponsereclamation r   where r.reponse like :nom  order by r.idrep ASC '
            );
            $query->setParameter('nom', $valueemail . '%');
        }


        return $query->getResult();
    }

    }
