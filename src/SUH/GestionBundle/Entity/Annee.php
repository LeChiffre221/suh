<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Annee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\AnneeRepository")
 *
 */
class Annee
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
     * @var string
     *
     * @ORM\Column(name="anneeUniversitaire", type="string", length=9, unique=true)
     */
    private $anneeUniversitaire;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set anneeUniversitaire
     *
     * @param string $anneeUniversitaire
     *
     * @return Annee
     */
    public function setAnneeUniversitaire($anneeUniversitaire)
    {
        $this->anneeUniversitaire = $anneeUniversitaire;

        return $this;
    }

    /**
     * Get anneeUniversitaire
     *
     * @return string
     */
    public function getAnneeUniversitaire()
    {
        return $this->anneeUniversitaire;
    }
}
