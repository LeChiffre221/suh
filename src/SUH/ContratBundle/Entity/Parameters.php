<?php

namespace SUH\ContratBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Parameters
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Parameters
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @var \adminMail
     *
     * @ORM\Column(name="adminMail")
     */
    private $adminMail;

    /**
     * @var \portMail
     *
     * @ORM\Column(name="portMail")
     */
    private $portMail;

    /**
     * @var \hostMail
     *
     * @ORM\Column(name="hostMail")
     */
    private $hostMail;

    /**
     * @var \usernameMail
     *
     * @ORM\Column(name="usernameMail")
     */
    private $usernameMail;

    /**
     * @var \passwordMail
     *
     * @ORM\Column(name="passwordMail")
     */
    private $passwordMail;

    /**
     * @var \prixHoraire
     *
     * @ORM\Column(name="prixHoraire", type="integer", nullable=false)
     */
    private $prixHoraire;


    /**
     * @var \coefTutorat
     *
     * @ORM\Column(name="coefTutorat", type="integer", nullable=false)
     */
    private $coefTutorat;

    /**
     * @var \coefPriseDeNote
     *
     * @ORM\Column(name="coefPriseDeNote", type="integer", nullable=false)
     */
    private $coefPriseDeNote;

    /**
     * @var \coefAssistance
     *
     * @ORM\Column(name="coefAssistance", type="integer", nullable=false)
     */
    private $coefAssistance;

    /**
     * @var \delaiMois
     *
     * @ORM\Column(name="delaiMois", type="integer", nullable=false)
     */
    private $delaiMois;

    /**
     * @var \dateMoisLimite
     *
     * @ORM\Column(name="dateMoisLimite", type="datetime", nullable=false)
     */
    private $dateMoisLimite;



    /**
     * Set prixHoraire
     *
     * @param integer $prixHoraire
     *
     * @return Parameters
     */
    public function setPrixHoraire($prixHoraire)
    {
        $this->prixHoraire = $prixHoraire;

        return $this;
    }

    /**
     * Get prixHoraire
     *
     * @return integer
     */
    public function getPrixHoraire()
    {
        return $this->prixHoraire;
    }

    /**
     * Set coefTutorat
     *
     * @param integer $coefTutorat
     *
     * @return Parameters
     */
    public function setCoefTutorat($coefTutorat)
    {
        $this->coefTutorat = $coefTutorat;

        return $this;
    }

    /**
     * Get coefTutorat
     *
     * @return integer
     */
    public function getCoefTutorat()
    {
        return $this->coefTutorat;
    }

    /**
     * Set coefPriseDeNote
     *
     * @param integer $coefPriseDeNote
     *
     * @return Parameters
     */
    public function setCoefPriseDeNote($coefPriseDeNote)
    {
        $this->coefPriseDeNote = $coefPriseDeNote;

        return $this;
    }

    /**
     * Get coefPriseDeNote
     *
     * @return integer
     */
    public function getCoefPriseDeNote()
    {
        return $this->coefPriseDeNote;
    }

    /**
     * Set coefAssistance
     *
     * @param integer $coefAssistance
     *
     * @return Parameters
     */
    public function setCoefAssistance($coefAssistance)
    {
        $this->coefAssistance = $coefAssistance;

        return $this;
    }

    /**
     * Get coefAssistance
     *
     * @return integer
     */
    public function getCoefAssistance()
    {
        return $this->coefAssistance;
    }

    /**
     * Set adminMail
     *
     * @param string $adminMail
     *
     * @return Parameters
     */
    public function setAdminMail($adminMail)
    {
        $this->adminMail = $adminMail;

        return $this;
    }

    /**
     * Get adminMail
     *
     * @return string
     */
    public function getAdminMail()
    {
        return $this->adminMail;
    }

    /**
     * Set portMail
     *
     * @param string $portMail
     *
     * @return Parameters
     */
    public function setPortMail($portMail)
    {
        $this->portMail = $portMail;

        return $this;
    }

    /**
     * Get portMail
     *
     * @return string
     */
    public function getPortMail()
    {
        return $this->portMail;
    }

    /**
     * Set hostMail
     *
     * @param string $hostMail
     *
     * @return Parameters
     */
    public function setHostMail($hostMail)
    {
        $this->hostMail = $hostMail;

        return $this;
    }

    /**
     * Get hostMail
     *
     * @return string
     */
    public function getHostMail()
    {
        return $this->hostMail;
    }

    /**
     * Set usernameMail
     *
     * @param string $usernameMail
     *
     * @return Parameters
     */
    public function setUsernameMail($usernameMail)
    {
        $this->usernameMail = $usernameMail;

        return $this;
    }

    /**
     * Get usernameMail
     *
     * @return string
     */
    public function getUsernameMail()
    {
        return $this->usernameMail;
    }

    /**
     * Set passwordMail
     *
     * @param string $passwordMail
     *
     * @return Parameters
     */
    public function setPasswordMail($passwordMail)
    {
        $this->passwordMail = $passwordMail;

        return $this;
    }

    /**
     * Get passwordMail
     *
     * @return string
     */
    public function getPasswordMail()
    {
        return $this->passwordMail;
    }

    /**
     * Set delaiMois
     *
     * @param integer $delaiMois
     *
     * @return Parameters
     */
    public function setDelaiMois($delaiMois)
    {
        $this->delaiMois = $delaiMois;

        return $this;
    }

    /**
     * Get delaiMois
     *
     * @return integer
     */
    public function getDelaiMois()
    {
        return $this->delaiMois;
    }


    /**
     * Set dateAndTime
     *
     * @param \DateTime $dateAndTime
     *
     * @return HeureEffectuee
     */
    public function setDateMoisLimite($dateMoisLimite)
    {

        $dateMoisLimite = date("m-d", strtotime(strtr($dateMoisLimite, '/', '-') ));
        $this->dateMoisLimite = new DateTime($dateMoisLimite);

        return $this;
    }

    /**
     * Get dateMoisLimite
     *
     * @return \dateMoisLimite
     */
    public function getDateMoisLimite()
    {

        if($this->dateMoisLimite instanceof \DateTime){
            return $this->dateMoisLimite->format('d/m');
        }

        return $this->dateMoisLimite;
    }


}
