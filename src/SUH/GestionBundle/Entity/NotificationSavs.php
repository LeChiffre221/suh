<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationSavs
 *
 * @ORM\Table(name="notificationsavs")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\NotificationSavsRepository")
 */
class NotificationSavs
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
     * @ORM\Column(name="notificationText", type="string", length=255, nullable=true)
     */
    private $notificationText;

    /* ====================================================================== */
    /* ====================================================================== */
    /* ====================================================================== */
    
    public function __construct($notificationText)
    {
        $this->notificationText=$notificationText;
    }

    //public function __construct(){}

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
     * Set notificationText
     *
     * @param string $notificationText
     *
     * @return NotificationSavs
     */
    public function setNotificationText($notificationText)
    {
        $this->notificationText = $notificationText;
    
        return $this;
    }

    /**
     * Get notificationText
     *
     * @return string
     */
    public function getNotificationText()
    {
        return $this->notificationText;
    }
}
