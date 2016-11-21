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

public function getPage($page, $maxepage=4, $id){

	    $q = $this->createQueryBuilder('a')
	    ->where('a.id = :id')
	    ->setParameter('id', $id)
 		->getQuery();

        $q->setFirstResult(($page-1) * $maxepage)
            ->setMaxResults($maxepage);
 
        return new Paginator($q);

}

}
