<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\Formation;
use SUH\GestionBundle\Entity\EtudiantFormation;



class EtudiantFormationTest extends WebTestCase
{
    private $etudiantFormation;

    protected function setUp()
    {
        $this->etudiant = new Etudiant("AAAABBBBCCCC0000","17/10/1989");
        $this->formation = new Formation("DUT","IUT","Info",2,"UBP",3);
        $this->etudiantFormation = new EtudiantFormation($this->etudiant,"2015-2016",$this->formation);
        
        $this->assertNotNull($this->etudiantFormation);

    }

    public function testGetId()
    {
        $this->assertNull($this->etudiantFormation->getId());
    }

    public function testSetId()
    {
        $this->etudiantFormation->setId(15);
        $this->assertEquals(15,$this->etudiantFormation->getId());
    }

    public function testGetEtudiant()
    {
        $this->assertEquals($this->etudiant, $this->etudiantFormation->getEtudiant());
    }

    public function testSetEtudiant()
    {
        $this->etudiant2 = new Etudiant("EGGZZ1714","17/12/1993");
        $this->etudiantFormation->setEtudiant($this->etudiant2);
        $this->assertEquals($this->etudiant2, $this->etudiantFormation->getEtudiant());
        //$this->assertContains($this->formation,$this->etudiantFormation->getEtudiant()->getListEtudiantFormation());
    }

    public function testGetAnneeScolaire()
    {
        $this->assertEquals("2015-2016", $this->etudiantFormation->getAnneeScolaire());
    }

    public function testSetAnneeScolaire()
    {
        $this->etudiantFormation->setAnneeScolaire("2014-2015");
        $this->assertEquals("2014-2015", $this->etudiantFormation->getAnneeScolaire());
    }

    public function testGetFormation()
    {
        $this->assertEquals($this->formation, $this->etudiantFormation->getFormation());
    }

    public function testSetFormation()
    {
        $this->formation2 = new Formation("Licence","UFR","Info",1,"UBP",3);
        $this->etudiantFormation->setFormation($this->formation2);
        $this->assertEquals("Licence", $this->formation2->getDiplome());
    }

}
