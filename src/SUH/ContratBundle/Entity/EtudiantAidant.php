<?php

namespace SUH\ContratBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EtudiantAidant
 *
 * @ORM\Table(name="etudiantaide")
 * @ORM\Entity(repositoryClass="SUH\ContratBundle\Entity\EtudiantAidantRepository")
 */
class EtudiantAidant
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\Etudiant", cascade={"persist"})
     * @Assert\Valid()
     */
    private $etudiant;

    /**
     * @var integer
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\EtudiantInformations", cascade={"persist"})
     * @Assert\Valid()
     */
    private $etudiantInformations;

    /**
     * @var integer
     * @ORM\OneToOne(targetEntity="SUH\GestionBundle\Entity\Formation", cascade={"persist"})
     * @Assert\Valid()
     */
    private $formation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="certificatMedical", type="boolean", nullable=true)
     * @Assert\Type(type="boolean")
     */
    private $certificatMedical;

    /**
     * @var integer
     * @ORM\OneToOne(targetEntity="SUH\ConnexionBundle\Entity\User", cascade={"persist"})
     * @Assert\Valid()
     */
    private $user;

    private $heureNonValide;

    /**
     * @ORM\ManyToMany(targetEntity="SUH\GestionBundle\Entity\Annee", cascade={"persist"})
     */

    private $annees;


    public function getId(){
        return $this->id;
    }

    /**
     * Set certificatMedical
     *
     * @param boolean $certificatMedical
     *
     * @return EtudiantAidant
     */
    public function setCertificatMedical($certificatMedical)
    {
        $this->certificatMedical = $certificatMedical;

        return $this;
    }

    /**
     * Get certificatMedical
     *
     * @return boolean
     */
    public function getCertificatMedical()
    {
        return $this->certificatMedical;
    }





    public function getEtudiant()
    {
      return $this->etudiant;
    }
    public function setEtudiant($etudiant){
      
      return $this->etudiant = $etudiant;
    }



    public function getFormation()
    {
    return $this->formation;
    }
    public function setFormation($formation){

    return $this->formation = $formation;
    }



    public function getEtudiantInformations()
    {
        return $this->etudiantInformations;
    }
    public function setEtudiantInformations($etudiantInformations){

    return $this->etudiantInformations = $etudiantInformations;
    }


  public function getEtudiantFormation()
  {
    return $this->formation;
  }
  public function setEtudiantFormation($formation){

    return $this->formation = $formation;

  }


    /**
     * Set user
     *
     * @param \SUH\GestionBundle\Entity\User $user
     *
     * @return EtudiantAidant
     */
    public function setUser(\SUH\ConnexionBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \SUH\ConnexionBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getHeureNonValide(){
        return $this->heureNonValide;
    }
    public function setHeureNonValide($heureNonValide){
        return $this->heureNonValide = $heureNonValide;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->annees = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add annee
     *
     * @param \SUH\GestionBundle\Entity\Annee $annee
     *
     * @return EtudiantAidant
     */
    public function addAnnee(\SUH\GestionBundle\Entity\Annee $annee)
    {
        $this->annees[] = $annee;

        return $this;
    }

    /**
     * Remove annee
     *
     * @param \SUH\GestionBundle\Entity\Annee $annee
     */
    public function removeAnnee(\SUH\GestionBundle\Entity\Annee $annee)
    {
        $this->annees->removeElement($annee);
    }

    /**
     * Get annees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnnees()
    {
        return $this->annees;
    }
}
