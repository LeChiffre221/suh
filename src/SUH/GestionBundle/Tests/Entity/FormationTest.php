<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\Formation;




class FormationTest extends WebTestCase
{
    private $etudiantFormation;

    protected function setUp()
    {
        $this->etudiant = new Etudiant("AAAABBBBCCCC0000","17/10/1989");
        $this->formation = new Formation("DUT","IUT","Info",2,"UBP",3);
        
        
        $this->assertNotNull($this->formation);

    }

    public function testGetId()
    {
        $this->assertNull($this->formation->getId());
    }

    public function testGetDiplome()
    {
        $this->assertEquals("DUT", $this->formation->getDiplome());
    }

    public function testSetDiplome()
    {
        $this->formation->setDiplome("Licence");
        $this->assertEquals("Licence", $this->formation->getDiplome());
    }

   public function testGetComposante()
    {
        $this->assertEquals("IUT", $this->formation->getComposante());
    }

    public function testSetComposante()
    {
        $this->formation->setComposante("UFR");
        $this->assertEquals("UFR", $this->formation->getComposante());
    }

    public function testGetFiliere()
    {
        $this->assertEquals("Info", $this->formation->getFiliere());
    }

    public function testSetFiliere()
    {
        $this->formation->setFiliere("Bio");
        $this->assertEquals("Bio", $this->formation->getFiliere());
    }

    public function testGetCycle()
    {
        $this->assertEquals(2, $this->formation->getCycle());
    }

    public function testSetCycle()
    {
        $this->formation->setCycle(3);
        $this->assertEquals(3, $this->formation->getCycle());
    }

    public function testGetEtablissement()
    {
        $this->assertEquals("UBP", $this->formation->getEtablissement());
    }

    public function testSetEtablissement()
    {
        $this->formation->setEtablissement("UDA");
        $this->assertEquals("UDA", $this->formation->getEtablissement());
    }

    public function testGetAnneeEtude()
    {
        $this->assertEquals(3, $this->formation->getAnneeEtude());
    }

    public function testSetAnneeEtude()
    {
        $this->formation->setAnneeEtude(4);
        $this->assertEquals(4, $this->formation->getAnneeEtude());
    }

}
