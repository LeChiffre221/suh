<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\AmenagementEtude;


class AmenagementEtudeTest extends WebTestCase
{
    private $amenagementEtude;

    protected function setUp()
    {
        $this->amenagementEtude = new AmenagementEtude("nouvelAmenagement","Une info","Un detail");

        $this->assertNotNull($this->amenagementEtude);
    }

    public function testGetId()
    {
        $this->assertNull($this->amenagementEtude->getId());
    }

    public function testGetNom()
    {
        $this->assertEquals("nouvelAmenagement", $this->amenagementEtude->getNom());
    }

    public function testSetNom()
    {
        $this->amenagementEtude->setNom("autreAmenagement");
        $this->assertEquals("autreAmenagement", $this->amenagementEtude->getNom());
    }

    public function testGetInformationComplementaire()
    {
        $this->assertEquals("Une info", $this->amenagementEtude->getInformationComplementaire());
    }

    public function testSetInformationComplementaire()
    {
        $this->amenagementEtude->setInformationComplementaire("NouvelleInfo");
        $this->assertEquals("NouvelleInfo", $this->amenagementEtude->getInformationComplementaire());
    }

   public function testGetDetail()
    {
        $this->assertEquals("Un detail", $this->amenagementEtude->getDetail());
    } 

    public function testSetDetail()
    {
        $this->amenagementEtude->setDetail("NouveauDetail");
        $this->assertEquals("NouveauDetail", $this->amenagementEtude->getDetail());
    }
}