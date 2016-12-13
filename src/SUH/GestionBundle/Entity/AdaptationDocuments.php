<?php

namespace SUH\GestionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdaptationDocuments
 *
 * @ORM\Table(name="adaptationdocuments")
 * @ORM\Entity(repositoryClass="SUH\GestionBundle\Entity\AdaptationDocumentsRepository")
 */
class AdaptationDocuments
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
     * @ORM\Column(name="detail", type="string", length=255)
     */
    private $detail;

    public function __construct($detail)
    {
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
     * Set detail
     *
     * @param string $detail
     *
     * @return AdaptationDocuments
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
