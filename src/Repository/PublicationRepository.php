<?php

namespace App\Repository;

class PublicationRepository
{
    public function findevent($valueemail,$order){
        $em = $this->getEntityManager();
        if($order=='DESC') {
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Publication r   where r.datepub like :datepub order by r.idpub DESC '
            );
            $query->setParameter('datepub', $valueemail . '%');
        }
        else{
            $query = $em->createQuery(
                'SELECT r FROM App\Entity\Publication r   where r.datepub like :datepub  order by r.idpub ASC '
            );
            $query->setParameter('datepub', $valueemail . '%');
        }
        return $query->getResult();
    }

}