<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\Formation;
use SUH\GestionBundle\Entity\EtudiantFormation;
use SUH\GestionBundle\Entity\EtudiantInformations;
use SUH\GestionBundle\Entity\EtudiantEtudiantInformations;
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

class EtudiantTest extends WebTestCase
{
    private $etudiant;

    protected function setUp()
    {
        $this->etudiant = new Etudiant("AAAABBBBCCCC0000","17/10/1989");
        $this->formation = new Formation("DUT","IUT","Info",2,"UBP",3);
        $this->etudiantFormation = new EtudiantFormation($this->etudiant,"2015-2016",$this->formation);
        
        $this->etudiantInformations = new EtudiantInformations("Spatola", "Vincent", "vinc@hotmail.fr", "Masculin", "vinc2@hotmail.fr", "vinc3@hotmail.fr", "adresseEtude", "adresseParents", "0630878978", "0471746343");
        $this->etudiantEtudiantInformations = new EtudiantEtudiantInformations($this->etudiant,"2015-2016",$this->etudiantInformations);

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
        $this->etudiantEtudiantHandicape = new EtudiantEtudiantHandicape($this->etudiant,"2015-2016",$this->etudiantHandicape);

        $this->assertNotNull($this->etudiant);

    }

    public function testGetId()
    {
        $this->assertNull($this->etudiant->getId());
    }

    public function testGetDateNaissance()
    {
        $this->assertEquals("17/10/1989", $this->etudiant->getDateNaissance());
    }

    public function testSetDateNaissance()
    {
        $this->etudiant->setDateNaissance("14/12/1995");
        $this->assertEquals("14/12/1995", $this->etudiant->getDateNaissance());
    }

    public function testGetNumeroEtudiant()
    {
        $this->assertEquals("AAAABBBBCCCC0000", $this->etudiant->getNumeroEtudiant());
    }

    public function testSetNumeroEtudiant()
    {
        $this->etudiant->setNumeroEtudiant("SSSS1234");
        $this->assertEquals("SSSS1234", $this->etudiant->getNumeroEtudiant());
    }

    public function testGetListEtudiantInformations()
    {
        $this->assertNotNull($this->etudiant->getListEtudiantInformations());
    }

    public function testAddListEtudiantInformations()
    {
        $this->etudiant->addListEtudiantInformations($this->etudiantEtudiantInformations);
        $this->assertContains($this->etudiantEtudiantInformations, $this->etudiant->getListEtudiantInformations());
    }

    public function testRemoveListEtudiantInformations()
    {
        $this->etudiant->removeListEtudiantInformations($this->etudiantEtudiantInformations);
        $this->assertCount(0, $this->etudiant->getListEtudiantInformations());
    }

    public function testGetListEtudiantFormation()
    {
        $this->assertNotNull($this->etudiant->getListEtudiantFormation());
    }

    public function testAddListEtudiantFormation()
    {
        $this->etudiant->addListEtudiantFormation($this->etudiantFormation);
        $this->assertContains($this->etudiantFormation, $this->etudiant->getListEtudiantFormation());
    }

    public function testRemoveListEtudiantFormation()
    {
        $this->etudiant->removeListEtudiantFormation($this->etudiantFormation);
        $this->assertCount(0, $this->etudiant->getListEtudiantFormation());
    }

    public function testGetListEtudiantHandicape()
    {
        $this->assertNotNull($this->etudiant->getListEtudiantHandicape());
    }

    public function testAddListEtudiantHandicape()
    {
        $this->etudiant->addListEtudiantHandicape($this->etudiantEtudiantHandicape);
        $this->assertContains($this->etudiantEtudiantHandicape, $this->etudiant->getListEtudiantHandicape());
    }

    public function testRemoveListEtudiantHandicape()
    {
        $this->etudiant->removeListEtudiantHandicape($this->etudiantEtudiantHandicape);
        $this->assertCount(0, $this->etudiant->getListEtudiantHandicape());
    }

}
