<?php

namespace SUH\ContratBundle\Entity;

/**
 * AvenantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AvenantRepository extends \Doctrine\ORM\EntityRepository
{

    public function getAvenantsPourUnContrat($contrat){
        $q = $this->createQueryBuilder('c')
            ->where('c.contrat = :contrat')
            ->setParameter('contrat', $contrat)
            ->getQuery()
            ->getResult();





        return $q;
    }
}
