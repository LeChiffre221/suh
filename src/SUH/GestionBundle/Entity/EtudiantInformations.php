<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EtudiantInformations
 *
 * @ORM\Table(name="etudiantinformations")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\EtudiantInformationsRepository")
 */
class EtudiantInformations
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
     * @Assert\Type(type="string")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\Type(type="string")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="mailInstitutionnel", type="string", length=255, nullable=true)
     * @Assert\Email(checkMX=true)
     */
    private $mailInstitutionnel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parite", type="string", length=1, nullable=true)
     */
    private $parite;

    /**
     * @var string
     *
     * @ORM\Column(name="mailPerso", type="string", length=255, nullable=true)
     * @Assert\Email(checkMX=true)
     */
    private $mailPerso;

    /**
     * @var string
     *
     * @ORM\Column(name="mailParents", type="string", length=255, nullable=true)
     * @Assert\Email(checkMX=true)
     */
    private $mailParents;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseEtudiante", type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $adresseEtudiante;

    /**
     * @var string
     *
     * @ORM\Column(name="adresseFamiliale", type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $adresseFamiliale;

    /**
     * @var string
     *
     * @ORM\Column(name="telephonePerso", type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $telephonePerso;

    /**
     * @var string
     *
     * @ORM\Column(name="telephoneParents", type="string", length=255, nullable=true)
     * @Assert\Type(type="string")
     */
    private $telephoneParents;


    public function __construct($nom=null, $prenom=null, $mailInstitutionnel=null, $parite=null, $mailPerso=null, $mailParents=null, $adresseEtudiante=null, $adresseFamiliale=null
        , $telephonePerso=null, $telephoneParents=null)

    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->mailInstitutionnel = $mailInstitutionnel;
        $this->parite = $parite;
        $this->mailPerso = $mailPerso;
        $this->mailParents = $mailParents;
        $this->adresseEtudiante =  $adresseEtudiante;
        $this->adresseFamiliale = $adresseFamiliale;
        $this->telephonePerso = $telephonePerso;
        $this->telephoneParents = $telephoneParents;
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
     * @return EtudiantInformations
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return EtudiantInformations
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set mailInstitutionnel
     *
     * @param string $mailInstitutionnel
     *
     * @return EtudiantInformations
     */
    public function setMailInstitutionnel($mailInstitutionnel)
    {
        $this->mailInstitutionnel = $mailInstitutionnel;

        return $this;
    }

    /**
     * Get mailInstitutionnel
     *
     * @return string
     */
    public function getMailInstitutionnel()
    {
        return $this->mailInstitutionnel;
    }

    /**
     * Set mailPerso
     *
     * @param string $mailPerso
     *
     * @return EtudiantInformations
     */
    public function setMailPerso($mailPerso)
    {
        $this->mailPerso = $mailPerso;

        return $this;
    }

    /**
     * Get mailPerso
     *
     * @return string
     */
    public function getMailPerso()
    {
        return $this->mailPerso;
    }

    /**
     * Get parite
     *
     * @return string
     */
    public function getParite()
    {
        return $this->parite;
    }

    /**
     * Set parite
     *
     * @param string $parite
     * @return string
     */
    public function setParite($parite)
    {
        $this->parite = $parite;

        return $this;
    }

    /**
     * Set mailParents
     *
     * @param string $mailParents
     *
     * @return EtudiantInformations
     */
    public function setMailParents($mailParents)
    {
        $this->mailParents = $mailParents;

        return $this;
    }

    /**
     * Get mailParents
     *
     * @return string
     */
    public function getMailParents()
    {
        return $this->mailParents;
    }

    /**
     * Set adresseEtudiante
     *
     * @param string $adresseEtudiante
     *
     * @return EtudiantInformations
     */
    public function setAdresseEtudiante($adresseEtudiante)
    {
        $this->adresseEtudiante = $adresseEtudiante;

        return $this;
    }

    /**
     * Get adresseEtudiante
     *
     * @return string
     */
    public function getAdresseEtudiante()
    {
        return $this->adresseEtudiante;
    }

    /**
     * Set adresseFamiliale
     *
     * @param string $adresseFamiliale
     *
     * @return EtudiantInformations
     */
    public function setAdresseFamiliale($adresseFamiliale)
    {
        $this->adresseFamiliale = $adresseFamiliale;

        return $this;
    }

    /**
     * Get adresseFamiliale
     *
     * @return string
     */
    public function getAdresseFamiliale()
    {
        return $this->adresseFamiliale;
    }

    /**
     * Set telephonePerso
     *
     * @param string $telephonePerso
     *
     * @return EtudiantInformations
     */
    public function setTelephonePerso($telephonePerso)
    {
        $this->telephonePerso = $telephonePerso;

        return $this;
    }

    /**
     * Get telephonePerso
     *
     * @return string
     */
    public function getTelephonePerso()
    {
        return $this->telephonePerso;
    }

    /**
     * Set telephoneParents
     *
     * @param string $telephoneParents
     *
     * @return EtudiantInformations
     */
    public function setTelephoneParents($telephoneParents)
    {
        $this->telephoneParents = $telephoneParents;

        return $this;
    }

    /**
     * Get telephoneParents
     *
     * @return string
     */
    public function getTelephoneParents()
    {
        return $this->telephoneParents;
    }
}
