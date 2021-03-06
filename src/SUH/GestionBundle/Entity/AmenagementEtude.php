<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AmenagementEtude
 *
 * @ORM\Table(name="amenagementetude")
 * @ORM\Entity
 */
class AmenagementEtude
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="informationComplementaire", type="string", length=255)
     */
    private $informationComplementaire;

    /**
     * @var string
     *
     * @ORM\Column(name="detail", type="string", length=255)
     */
    private $detail;

    public function __construct($nom, $informationComplementaire, $detail)
    {
        $this->nom = $nom;
        $this->informationComplementaire = $informationComplementaire;
        $this->detail = $detail;
    }


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
     * Set nom
     *
     * @param string $nom
     *
     * @return AmenagementEtude
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set informationComplementaire
     *
     * @param string $informationComplementaire
     *
     * @return AmenagementEtude
     */
    public function setInformationComplementaire($informationComplementaire)
    {
        $this->informationComplementaire = $informationComplementaire;

        return $this;
    }

    /**
     * Get informationComplementaire
     *
     * @return string
     */
    public function getInformationComplementaire()
    {
        return $this->informationComplementaire;
    }

    /**
     * Set detail
     *
     * @param string $detail
     *
     * @return AmenagementEtude
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

        return $this;
    }

    /**
     * Get detail
     *
     * @return string
     */
    public function getDetail()
    {
        return $this->detail;
    }
}
