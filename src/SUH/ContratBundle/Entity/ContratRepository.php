<?php

namespace SUH\ContratBundle\Entity;

use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * ContratRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContratRepository extends \Doctrine\ORM\EntityRepository
{

    public function getContratsPourUnEtudiant($etudiant){
        $q = $this->createQueryBuilder('c')
            ->where('c.etudiantAidant = :etudiant')
            ->setParameter('etudiant', $etudiant)
            ->andWhere('c.active = :active')
            ->setParameter('active' , true);



        return $q;
    }

    public function getPage($page, $maxpage, $id){

            $q = $this->createQueryBuilder('a')
            ->where('a.etudiantAidant = :id')
            ->andWhere('a.active = 1')
            ->setParameter('id', $id)
            ->orderBy('a.dateDebutContrat', 'DESC')
            ->getQuery();

            $q->setFirstResult(($page-1) * $maxpage)
                ->setMaxResults($maxpage);

            return new Paginator($q);

    }

    public function getPageArchive($page, $maxpage, $id){

    	    $a = $this->createQueryBuilder('a')
    	    ->where('a.etudiantAidant = :id')
            ->andWhere('a.active = 0')
            ->andWhere('a.miseEnPaiement = 0')
    	    ->setParameter('id', $id)
            ->orderBy('a.dateDebutContrat', 'DESC')
     		->getQuery();

            $a->setFirstResult(($page-1) * $maxpage)
                ->setMaxResults($maxpage);
     
            return new Paginator($a);

    }

    public function getPageMiseEnPaiement($id){

            return $this->createQueryBuilder('a')
            ->where('a.etudiantAidant = :id')
            ->andWhere('a.miseEnPaiement = 1')
            ->setParameter('id', $id)
            ->orderBy('a.dateDebutContrat', 'DESC')
            ->getQuery()
            ->getResult();

    }

}
