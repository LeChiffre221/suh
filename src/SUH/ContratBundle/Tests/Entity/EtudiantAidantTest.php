<?php

namespace SUH\ContratBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Etudiant;
use SUH\GestionBundle\Entity\EtudiantInformations;
use SUH\GestionBundle\Entity\Formation;
use SUH\ConnexionBundle\Entity\User;
use SUH\ContratBundle\Entity\EtudiantAidant;

class EtudiantAidantTest extends WebTestCase
{


    protected function setUp()
    {

        $this->etudiantAidant = new EtudiantAidant();

        $this->assertNotNull( $this->etudiantAidant);

    }



    public function testFalseGetCertificatMedical()
    {
        $this->assertFalse(false, $this->etudiantAidant->getCertificatMedical());
    }

    public function testTrueSetCertificatMedical()
    {
    	$this->etudiantAidant->setCertificatMedical(true);
        $this->assertNotNull($this->etudiantAidant->getCertificatMedical());
    }

    public function testFalseSetCertificatMedical()
    {
        $this->etudiantAidant->setCertificatMedical(false);
        $this->assertEquals(false, $this->etudiantAidant->getCertificatMedical());
    }
}

?>