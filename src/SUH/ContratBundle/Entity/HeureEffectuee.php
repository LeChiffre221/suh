<?php

namespace SUH\ContratBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * HeureEffectuee
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="SUH\ContratBundle\Entity\HeureEffectueeRepository")
 */
class HeureEffectuee
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
     * @ORM\Column(name="natureMission", type="string", length=255)
     */
    private $natureMission;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptionMission", type="string", length=1000, nullable = true)
     */
    private $descriptionMission;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAndTime", type="datetime")
     */
    private $dateAndTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbHeure", type="integer")
     */
    private $nbHeure;

    /**
     * @var boolean
     *
     * @ORM\Column(name="verification", type="boolean")
     */
    private $verification;

    /**
     * @var boolean
     *
     * @ORM\Column(name="heurePayee", type="boolean")
     */
    private $heurePayee;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="SUH\ContratBundle\Entity\Contrat", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $contrat;


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
     * Set natureMission
     *
     * @param string $natureMission
     *
     * @return HeureEffectuee
     */
    public function setNatureMission($natureMission)
    {
        $this->natureMission = $natureMission;
    
        return $this;
    }

    /**
     * Get natureMission
     *
     * @return string
     */
    public function getNatureMission()
    {
        return $this->natureMission;
    }

    /**
     * Set descriptionMission
     *
     * @param string $descriptionMission
     *
     * @return HeureEffectuee
     */
    public function setDescriptionMission($descriptionMission)
    {
        $this->descriptionMission = $descriptionMission;
    
        return $this;
    }

    /**
     * Get descriptionMission
     *
     * @return string
     */
    public function getDescriptionMission()
    {
        return $this->descriptionMission;
    }

    /**
     * Set dateAndTime
     *
     * @param \DateTime $dateAndTime
     *
     * @return HeureEffectuee
     */
    public function setDateAndTime($dateAndTime)
    {

        $dateAndTime = date("Y-m-d H:i", strtotime(strtr($dateAndTime, '/', '-') ));
        $this->dateAndTime = new DateTime($dateAndTime);

        return $this;
    }

    /**
     * Get dateAndTime
     *
     * @return \DateTime
     */
    public function getDateAndTime()
    {

        if($this->dateAndTime instanceof \DateTime){
            return $this->dateAndTime->format('d/m/Y H:i');
        }

        return $this->dateAndTime;
    }

    /**
     * Set nbHeure
     *
     * @param integer $nbHeure
     *
     * @return HeureEffectuee
     */
    public function setNbHeure($nbHeure)
    {
        $this->nbHeure = $nbHeure;
    
        return $this;
    }

    /**
     * Get nbHeure
     *
     * @return integer
     */
    public function getNbHeure()
    {
        return $this->nbHeure;
    }

    /**
     * Set verification
     *
     * @param boolean $verification
     *
     * @return HeureEffectuee
     */
    public function setVerification($verification)
    {
        $this->verification = $verification;
    
        return $this;
    }

    /**
     * Get verification
     *
     * @return boolean
     */
    public function getVerification()
    {
        return $this->verification;
    }

    /**
     * Set heurePayee
     *
     * @param boolean $verification
     *
     * @return HeureEffectuee
     */
    public function setHeurePayee($heurePayee)
    {
        $this->heurePayee = $heurePayee;
    
        return $this;
    }

    /**
     * Get heurePayee
     *
     * @return boolean
     */
    public function getHeurePayee()
    {
        return $this->heurePayee;
    }

    /**
     * Set contrat
     *
     * @param \SUH\ContratBundle\Entity\Contrat $contrat
     *
     * @return HeureEffectuee
     */
    public function setContrat(\SUH\ContratBundle\Entity\Contrat $contrat = null)
    {


        $this->contrat = $contrat;
    
        return $this;
    }

    /**
     * Get contrat
     *
     * @return \SUH\ContratBundle\Entity\Contrat
     */
    public function getContrat()
    {
        return $this->contrat;
    }
}
