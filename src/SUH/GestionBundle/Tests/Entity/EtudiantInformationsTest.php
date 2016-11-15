<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\EtudiantInformations;


class EtudiantInformationsTest extends WebTestCase
{
    private $etudiantInformations;

    protected function setUp()
    {
        $this->etudiant = new Etudiant("AAAABBBBCCCC0000","17/10/1989");
        
        $this->etudiantInformations = new EtudiantInformations("Spatola", "Vincent", "vinc@hotmail.fr", "Masculin", "vinc2@hotmail.fr", "vinc3@hotmail.fr", "adresseEtude", "adresseParents", "0630878978", "0471746343");
        

        
        $this->assertNotNull($this->etudiantInformations);

    }

    public function testGetId()
    {
        $this->assertNull($this->etudiantInformations->getId());
    }

    public function testGetNom()
    {
        $this->assertEquals("Spatola", $this->etudiantInformations->getNom());
    }

    public function testSetNom()
    {
        $this->etudiantInformations->setNom("Trémouillère");
        $this->assertEquals("Trémouillère", $this->etudiantInformations->getNom());
    }

    public function testGetPrenom()
    {
        $this->assertEquals("Vincent", $this->etudiantInformations->getPrenom());
    }

    public function testSetPrenom()
    {
        $this->etudiantInformations->setPrenom("Laurent");
        $this->assertEquals("Laurent", $this->etudiantInformations->getPrenom());
    }

    public function testGetMailInstitutionnel()
    {
        $this->assertEquals("vinc@hotmail.fr", $this->etudiantInformations->getMailInstitutionnel());
    }

    public function testSetMailInstitutionnel()
    {
        $this->etudiantInformations->setMailInstitutionnel("vinc@hotmail2.fr");
        $this->assertEquals("vinc@hotmail2.fr", $this->etudiantInformations->getMailInstitutionnel());
    }

    public function testGetMailPerso()
    {
        $this->assertEquals("vinc2@hotmail.fr", $this->etudiantInformations->getMailPerso());
    }

    public function testSetMailPerso()
    {
        $this->etudiantInformations->setMailPerso("vinc2bis@hotmail.fr");
        $this->assertEquals("vinc2bis@hotmail.fr", $this->etudiantInformations->getMailPerso());
    }

    public function testGetParite()
    {
        $this->assertEquals("Masculin", $this->etudiantInformations->getParite());
    }

    public function testSetParite()
    {
        $this->etudiantInformations->setParite("F");
        $this->assertEquals("F", $this->etudiantInformations->getParite());
    }

    public function testGetMailParents()
    {
        $this->assertEquals("vinc3@hotmail.fr", $this->etudiantInformations->getMailParents());
    }

    public function testSetMailParents()
    {
        $this->etudiantInformations->setMailParents("vinc3bis@hotmail.fr");
        $this->assertEquals("vinc3bis@hotmail.fr", $this->etudiantInformations->getMailParents());
    }

    public function testGetAdresseEtudiante()
    {
        $this->assertEquals("adresseEtude", $this->etudiantInformations->getAdresseEtudiante());
    }

    public function testSetAdresseEtudiante()
    {
        $this->etudiantInformations->setAdresseEtudiante("adresseEtude2");
        $this->assertEquals("adresseEtude2", $this->etudiantInformations->getAdresseEtudiante());
    }

    public function testGetAdresseFamiliale()
    {
        $this->assertEquals("adresseParents", $this->etudiantInformations->getAdresseFamiliale());
    }

    public function testSetAdresseFamiliale()
    {
        $this->etudiantInformations->setAdresseFamiliale("adresseParents2");
        $this->assertEquals("adresseParents2", $this->etudiantInformations->getAdresseFamiliale());
    }

    public function testGetTelephonePerso()
    {
        $this->assertEquals("0630878978", $this->etudiantInformations->getTelephonePerso());
    }

    public function testSetTelephonePerso()
    {
        $this->etudiantInformations->setTelephonePerso("0630878979");
        $this->assertEquals("0630878979", $this->etudiantInformations->getTelephonePerso());
    }

    public function testGetTelephoneParents()
    {
        $this->assertEquals("0471746343", $this->etudiantInformations->getTelephoneParents());
    }

    public function testSetTelephoneParents()
    {
        $this->etudiantInformations->setTelephoneParents("0471746345");
        $this->assertEquals("0471746345", $this->etudiantInformations->getTelephoneParents());
    }


    

}

        