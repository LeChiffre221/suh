<?php

namespace SUH\ContratBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserEtudiantAidant
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UserEtudiantAidant
{
    /**
     * @var integer
     *
     * @ORM\Column(name="idEtudiant", type="integer")
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="SUH\ConnexionBundle\Entity\EtudiantAidant",cascade={"persist"})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEtudiant;


    /**
     * @ORM\OneToOne(targetEntity="SUH\ConnexionBundle\Entity\User",cascade={"persist"})
     */
    private $id;


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

