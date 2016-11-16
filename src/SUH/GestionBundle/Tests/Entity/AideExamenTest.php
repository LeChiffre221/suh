<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\AideExamen;
use SUH\GestionBundle\Entity\Ordinateur;
use SUH\GestionBundle\Entity\AdaptationDocuments;
use SUH\GestionBundle\Entity\Materiel;
use SUH\GestionBundle\Entity\SecretaireExamen;
use SUH\GestionBundle\Entity\DelocalisationExamen;

class AideExamenTest extends WebTestCase
{
    private $aideExamen;

    protected function setUp()
    {
        $aideExamen = null;
        $adaptationDocuments=null;
        $materiel=null;
        $secretaireExamen=null;
        $delocalisationExamen=null;
        $amenagementExamen="amenagement";
        $avisMedical="avisMedical";
        $tempsMajore=true;
        $dateValidite="13/10/2015";
        $dureeAvisMedical="2 ans";
        $detail="detail";
        $nom="nomMateriel";
        $type="typeOrdinateur";
        $fonction="fonction";
        $lieu="lieu";
        $detail="detailLieu";
        $type4="type4";

        

        $this->ordi=new Ordinateur($type4);
        
        $adaptationDocuments=new AdaptationDocuments($detail);
        $this->adaptationDocuments2=new AdaptationDocuments("detail2");
       
        $materiel=new Materiel($nom);
    
        $secretaireExamen=new SecretaireExamen($fonction);
        $this->secretaireExamen2=new SecretaireExamen("fonction2");
        
        $delocalisationExamen=new DelocalisationExamen($lieu,$detail);
        $this->delocalisationExamen2=new DelocalisationExamen("le lieu","le détail");

        $this->aideExamen = new AideExamen($amenagementExamen,$tempsMajore,$secretaireExamen,
            $delocalisationExamen, $avisMedical, $dateValidite,$dureeAvisMedical, $materiel);
      

        $this->assertNotNull($this->aideExamen);
    }

    public function testGetId()
    {
        $this->assertNull($this->aideExamen->getId());
    }

    public function testGetAvisMedical()
    {
        $this->assertEquals("avisMedical", $this->aideExamen->getAvisMedical());
    }

    public function testSetAvisMedical()
    {
        $this->aideExamen->setAvisMedical("autreAvisMedical");
        $this->assertEquals("autreAvisMedical", $this->aideExamen->getAvisMedical());
    }

    public function testGetMateriel()
    {
        $this->assertNotNull($this->aideExamen->getMateriel());
    }

    public function testSetMateriel()
    {
        $this->aideExamen->setMateriel(new Materiel('nouveauMateriel'));
        $this->assertEquals("nouveauMateriel",$this->aideExamen->getMateriel()->getNom());
    }

    public function testGetOrdinateur()
    {
        $this->assertNotNull($this->aideExamen->getOrdinateur());
    }


    public function testAddOrdinateur()
    {
        $this->aideExamen->addOrdinateur($this->ordi);
        $this->assertContains($this->ordi,$this->aideExamen->getOrdinateur());
    }

    public function testRemoveOrdinateur()
    {
        $this->aideExamen->removeOrdinateur($this->ordi);
        $this->assertCount(0,$this->aideExamen->getOrdinateur());
    }

    public function testGetAdaptationDocuments()
    {
        $this->assertNotNull($this->aideExamen->getAdaptationDocuments());
    }


    public function testAddAdaptationDocuments()
    {

        $this->aideExamen->addAdaptationDocuments($this->adaptationDocuments2);
        $this->assertContains($this->adaptationDocuments2,$this->aideExamen->getAdaptationDocuments());
    }

    public function testRemoveAdaptationDocuments()
    {
        $this->aideExamen->removeAdaptationDocuments($this->adaptationDocuments2);
        $this->assertCount(0,$this->aideExamen->getAdaptationDocuments());
    }

    public function testGetAmenagementExamens()
    {
        $this->assertEquals("amenagement", $this->aideExamen->getAmenagementExamens());
    }

    public function testSetAmenagementExamens()
    {
        $this->aideExamen->setAmenagementExamens("autreAmenagement");
        $this->assertEquals("autreAmenagement", $this->aideExamen->getAmenagementExamens());
    }

    public function testGetTempsMajore()
    {
        $this->assertEquals(true, $this->aideExamen->getTempsMajore());
    }

    public function testSetTempsMajore()
    {
        $this->aideExamen->setTempsMajore(false);
        $this->assertEquals(false, $this->aideExamen->getTempsMajore());
    }

    public function testGetSecretaireExamen()
    {
        $this->assertNotNull($this->aideExamen->getSecretaireExamen());
    }

    public function testSetSecretaireExamen()
    {
        $this->aideExamen->setSecretaireExamen(array($this->secretaireExamen2));
        $this->assertContains($this->secretaireExamen2, $this->aideExamen->getSecretaireExamen());
    }

    public function testGetDelocalisationExamen()
    {
        $this->assertNotNull($this->aideExamen->getDelocalisationExamen());
    }

    public function testSetDelocalisationExamen()
    {
        $this->aideExamen->setDelocalisationExamen(array($this->delocalisationExamen2));
        $this->assertContains($this->delocalisationExamen2, $this->aideExamen->getDelocalisationExamen());
    }

    public function testGetDateValidite()
    {
        $this->assertEquals("13/10/2015",$this->aideExamen->getDateValidite());
    }

    public function testSetDateValidite()
    {
        $this->aideExamen->setDateValidite("11/06/2017");
        $this->assertContains("11/06/2017", $this->aideExamen->getDateValidite());
    }

    public function testGetDureeAvisMedical()
    {
        $this->assertEquals("2 ans",$this->aideExamen->getDureeAvisMedical());
    }

    public function testSetDureeAvisMedical()
    {
        $this->aideExamen->setDureeAvisMedical("3 ans");
        $this->assertContains("3 ans", $this->aideExamen->getDureeAvisMedical());
    }

}
