<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\EtudiantInformations;
use SUH\GestionBundle\Entity\EtudiantEtudiantInformations;


class EtudiantEtudiantInformationsTest extends WebTestCase
{
    private $etudiantEtudiantInformations;

    protected function setUp()
    {
        $this->etudiant = new Etudiant("AAAABBBBCCCC0000","17/10/1989");
       
        
        $this->etudiantInformations = new EtudiantInformations("Spatola", "Vincent", "vinc@hotmail.fr", "Masculin", "vinc2@hotmail.fr", "vinc3@hotmail.fr", "adresseEtude", "adresseParents", "0630878978", "0471746343");
        $this->etudiantInformations2 = new EtudiantInformations("Picot", "Alexandre", "alex@hotmail.fr", "Masculin", "alex2@hotmail.fr", "alex3@hotmail.fr", "adresseEtude2", "adresseParents2", "0630878943", "0471746356");
        $this->etudiantEtudiantInformations = new EtudiantEtudiantInformations($this->etudiant,"2015-2016",$this->etudiantInformations);

       
        
        $this->etudiantEtudiantHandicape = new EtudiantEtudiantInformations($this->etudiant,"2015-2016",$this->etudiantInformations);

        $this->assertNotNull($this->etudiantEtudiantHandicape);

    }

    public function testGetEtudiant()
    {
        $this->assertEquals($this->etudiant, $this->etudiantEtudiantHandicape->getEtudiant());
    }

    public function testSetEtudiant()
    {
        $this->etudiant2 = new Etudiant("EGGZZ1714","17/12/1993");
        $this->etudiantEtudiantHandicape->setEtudiant(array($this->etudiant2));
        $this->assertContains($this->etudiant2, $this->etudiantEtudiantHandicape->getEtudiant());
    }

    public function testGetAnneeScolaire()
    {
        $this->assertEquals("2015-2016", $this->etudiantEtudiantInformations->getAnneeScolaire());
    }

    public function testSetAnneeScolaire()
    {
        $this->etudiantEtudiantInformations->setAnneeScolaire("2014-2015");
        $this->assertEquals("2014-2015", $this->etudiantEtudiantInformations->getAnneeScolaire());
    }

    public function testGetEtudiantInformations()
    {
        $this->assertEquals($this->etudiantInformations, $this->etudiantEtudiantInformations->getEtudiantInformations());
    }

    public function testSetEtudiantInformations()
    {
        $this->etudiantEtudiantInformations->setEtudiantInformations(array($this->etudiantInformations2));
        $this->assertContains($this->etudiantInformations2, $this->etudiantEtudiantInformations->getEtudiantInformations());
    }

}
