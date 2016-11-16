<?php
/**
 * Created by PhpStorm.
 * User: Lechiffre
 * Date: 09/11/2016
 * Time: 14:09
 */

namespace SUH\GestionBundle\Entity;


use Doctrine\ORM\EntityRepository;

class AnneeRepository extends EntityRepository
{
    public function myFindAll()
    {
        $query = $this->_em->createQuery('SELECT a FROM SUHGestionBundle:Annee a
                                          ORDER BY a.anneeUniversitaire DESC');
        $results = $query->getResult();


        return $results;
    }
}