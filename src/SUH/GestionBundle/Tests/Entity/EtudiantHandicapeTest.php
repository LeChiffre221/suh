<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\EtudiantHandicape;
use SUH\GestionBundle\Entity\EtudiantEtudiantHandicape;
use SUH\GestionBundle\Entity\Materiel;
use SUH\GestionBundle\Entity\SecretaireExamen;
use SUH\GestionBundle\Entity\DelocalisationExamen;
use SUH\GestionBundle\Entity\Handicap;
use SUH\GestionBundle\Entity\Mdph;
use SUH\GestionBundle\Entity\NotificationSavs;
use SUH\GestionBundle\Entity\AmenagementEtude;
use SUH\GestionBundle\Entity\AideExamen;

class EtudiantHandicapeTest extends WebTestCase
{
    private $etudiantHandicape;

    protected function setUp()
    {
        $this->etudiant = new Etudiant("AAAABBBBCCCC0000","17/10/1989");
        
        $anneeScolaire="2015-2016";
       

        $this->notificationSavs = new NotificationSavs("notif");
        $this->notificationSavs2 = new NotificationSavs("notif2");
        $this->amenagementEtude = new AmenagementEtude("nouvelAmenagement","Une info","Un detail");
        $this->amenagementEtude2 = new AmenagementEtude("nouvelAmenagement2","Une info2","Un detail2");
        $this->mdph = new Mdph("mdph");
        $this->handicap = new Handicap("handicap");
        $this->handicap2 = new Handicap("handicap2");
        $this->secretaireExamen=new SecretaireExamen("fonction");
        
        $this->delocalisationExamen=new DelocalisationExamen("lieu","detail");
        $this->materiel=new Materiel("nom");
        $aideExamen = new AideExamen(true,true,$this->secretaireExamen,
            $this->delocalisationExamen, "avisMedical", "13/10/2015","2 ans", $this->materiel);
        $this->etudiantHandicape = new EtudiantHandicape("qhandi","rqth",$this->notificationSavs,$this->amenagementEtude,
            "tauxInvalidite","suivi","typeAllocations","descriptifComplementaire",$this->mdph,$this->handicap,true, false, false, $aideExamen);
        

        $this->assertNotNull($this->etudiantHandicape);

    }

    public function testGetId()
    {
        $this->assertNull($this->etudiantHandicape->getId());
    }

    public function testGetAideExamen()
    {
        $this->assertNotNull($this->etudiantHandicape->getAideExamen());
    }

    public function testSetAideExamen()
    {
        $this->etudiantHandicape->setAideExamen(new AideExamen(true,true,$this->secretaireExamen,
            $this->delocalisationExamen, "avisMedical2", "15/10/2015","3 ans", $this->materiel));
        $this->assertEquals("15/10/2015",$this->etudiantHandicape->getAideExamen()->getDateValidite());
    }

    public function testGetAmenagementEtude()
    {
        $this->assertNotNull($this->etudiantHandicape->getAmenagementEtude());
    }

    public function testAddAmenagementEtude()
    {
        $this->etudiantHandicape->addAmenagementEtude($this->amenagementEtude2);
        $this->assertContains($this->amenagementEtude2,$this->etudiantHandicape->getAmenagementEtude());
    }

    public function testRemoveAmenagementEtude()
    {
        $this->etudiantHandicape->removeAmenagementEtude($this->amenagementEtude2);
        $this->assertCount(1,$this->etudiantHandicape->getAmenagementEtude());
    }

    public function testGetHandicap()
    {
        $this->assertNotNull($this->etudiantHandicape->getHandicap());
    }

    public function testAddHandicap()
    {
        $this->etudiantHandicape->addHandicap($this->handicap2);
        $this->assertContains($this->handicap2,$this->etudiantHandicape->getHandicap());
    }

    public function testRemoveHandicap()
    {
        $this->etudiantHandicape->removeHandicap($this->handicap2);
        $this->assertCount(1,$this->etudiantHandicape->getHandicap());
    }

    public function testGetMdph()
    {
        $this->assertNotNull($this->etudiantHandicape->getMdph());
    }

    public function testSetMdph()
    {
        $this->etudiantHandicape->setMdph("mdph2");
        $this->assertEquals("mdph2",$this->etudiantHandicape->getMdph());
    }

    public function testGetQhandi()
    {
        $this->assertNotNull($this->etudiantHandicape->getQhandi());
    }

    public function testSetQhandi()
    {
        $this->etudiantHandicape->setQhandi("qhandi2");
        $this->assertEquals("qhandi2",$this->etudiantHandicape->getQhandi());
    }

    public function testGetDemandeMdphEnCours()
    {
        $this->assertNotNull($this->etudiantHandicape->getDemandeMdphEnCours());
    }

    public function testSetDemandeMdphEnCours()
    {
        $this->etudiantHandicape->setDemandeMdphEnCours(false);
        $this->assertEquals(false,$this->etudiantHandicape->getDemandeMdphEnCours());
    }

    public function testGetDemandeRqthEnCours()
    {
        $this->assertNotNull($this->etudiantHandicape->getDemandeRqthEnCours());
    }

    public function testSetDemandeRqthEnCours()
    {
        $this->etudiantHandicape->setDemandeRqthEnCours(false);
        $this->assertEquals(false,$this->etudiantHandicape->getDemandeRqthEnCours());
    }

    public function testGetDemandeNotificationSavsEnCours()
    {
        $this->assertNotNull($this->etudiantHandicape->getDemandeNotificationSavsEnCours());
    }

    public function testSetDemandeNotificationSavsEnCours()
    {
        $this->etudiantHandicape->setDemandeNotificationSavsEnCours(false);
        $this->assertEquals(false,$this->etudiantHandicape->getDemandeNotificationSavsEnCours());
    }

    public function testGetNotificationSavs()
    {
        $this->assertNotNull($this->etudiantHandicape->getNotificationSavs());
    }

    public function testSetNotificationSavs()
    {
        $this->etudiantHandicape->setNotificationSavs($this->notificationSavs2);
        $this->assertEquals($this->notificationSavs2,$this->etudiantHandicape->getNotificationSavs());
    }
    
    public function testSetAmenagementEtude()
    {
        $this->etudiantHandicape->setAmenagementEtude(array($this->amenagementEtude2));
        $this->assertContains($this->amenagementEtude2,$this->etudiantHandicape->getAmenagementEtude());
    }

    public function testGetTauxInvalidite()
    {
        $this->assertNotNull($this->etudiantHandicape->getTauxInvalidite());
    }

    public function testSetTauxInvalidite()
    {
        $this->etudiantHandicape->setTauxInvalidite("taux");
        $this->assertEquals("taux",$this->etudiantHandicape->getTauxInvalidite());
    }

    public function testGetSuivi()
    {
        $this->assertNotNull($this->etudiantHandicape->getSuivi());
    }

    public function testSetSuivi()
    {
        $this->etudiantHandicape->setSuivi("suivi2");
        $this->assertEquals("suivi2",$this->etudiantHandicape->getSuivi());
    }

    public function testGetDescriptifComplementaire()
    {
        $this->assertNotNull($this->etudiantHandicape->getDescriptifComplementaire());
    }

    public function testSetDescriptifComplementaire()
    {
        $this->etudiantHandicape->setDescriptifComplementaire("descriptif2");
        $this->assertEquals("descriptif2",$this->etudiantHandicape->getDescriptifComplementaire());
    }

    public function testGetTypeAllocations()
    {
        $this->assertNotNull($this->etudiantHandicape->getTypeAllocations());
    }

    public function testSetTypeAllocations()
    {
        $this->etudiantHandicape->setTypeAllocations("type2");
        $this->assertEquals("type2",$this->etudiantHandicape->getTypeAllocations());
    }

}
