<?php

namespace SUH\ContratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtudiantAidant
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class EtudiantAidant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var boolean
     *
     * @ORM\Column(name="certificatMedical", type="boolean")
     */
    private $certificatMedical = false;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}

