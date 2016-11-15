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

class EtudiantEtudiantHandicapeTest extends WebTestCase
{
    private $etudiantEtudiantHandicape;

    protected function setUp()
    {
        $this->etudiant = new Etudiant("AAAABBBBCCCC0000","17/10/1989");
        
        $anneeScolaire="2015-2016";
       

        $this->notificationSavs = new NotificationSavs("notif");
        $this->amenagementEtude = new AmenagementEtude("nouvelAmenagement","Une info","Un detail");
        $this->mdph = new Mdph("mdph");
        $this->handicap = new Handicap("handicap");
        $secretaireExamen=new SecretaireExamen("fonction");
        
        $delocalisationExamen=new DelocalisationExamen("lieu","detail");
        $materiel=new Materiel("nom");
        $aideExamen = new AideExamen(true,true,$secretaireExamen,
            $delocalisationExamen, "avisMedical", "13/10/2015","2 ans", $materiel);
        $this->etudiantHandicape = new EtudiantHandicape("qhandi","rqth",$this->notificationSavs,$this->amenagementEtude,
            "tauxInvalidite","suivi","typeAllocations","descriptifComplementaire",$this->mdph,$this->handicap,true, false, false, $aideExamen);
        $this->etudiantHandicape2 = new EtudiantEtudiantHandicape("qhandi2","rqth2",$this->notificationSavs,$this->amenagementEtude,
            "tauxInvalidite2","suivi2","typeAllocations2","descriptifComplementaire2",$this->mdph,$this->handicap,true, false, false, $aideExamen);
        $this->etudiantEtudiantHandicape = new EtudiantEtudiantHandicape($this->etudiant,"2015-2016",$this->etudiantHandicape);
        

        $this->assertNotNull($this->etudiantEtudiantHandicape);

    }
//**********************************************
    public function testGetEtudiant()
    {
        $this->assertEquals($this->etudiant, $this->etudiantEtudiantHandicape->getEtudiant());
    }

    public function testSetEtudiant()
    {
        $this->etudiant2 = new Etudiant("EGGZZ1714","17/12/1993");
        $this->etudiantEtudiantHandicape->setEtudiant($this->etudiant2);
      //  $this->assertContains($this->etudiant2, $this->etudiantEtudiantHandicape->getEtudiant());
    }

    public function testGetAnneeScolaire()
    {
        $this->assertEquals("2015-2016", $this->etudiantEtudiantHandicape->getAnneeScolaire());
    }

    public function testSetAnneeScolaire()
    {
        $this->etudiantEtudiantHandicape->setAnneeScolaire("2014-2015");
        $this->assertEquals("2014-2015", $this->etudiantEtudiantHandicape->getAnneeScolaire());
    }

    public function testGetEtudiantHandicape()
    {
        $this->assertEquals($this->etudiantHandicape, $this->etudiantEtudiantHandicape->getEtudiantHandicape());
    }

    public function testSetEtudiantHandicape()
    {
        $this->etudiantEtudiantHandicape->setEtudiantHandicape(array($this->etudiantHandicape2));
        $this->assertContains($this->etudiantHandicape2, $this->etudiantEtudiantHandicape->getEtudiantHandicape());
    }
}
