<?php

namespace SUH\GestionBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\EtudiantEtudiantInformations;
use SUH\GestionBundle\Entity\EtudiantRepository;





class EtudiantRepositoryTest extends WebTestCase
{
    /**
     * @var \SUH\GestionBundle\Entity\EtudiantRepository
     */
    private $etudiantRepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->etudiantRepository = $kernel->getContainer()
                                       ->get('doctrine.orm.entity_manager')
                                       ->getRepository('SUHGestionBundle:Etudiant');
    }

    public function testGetAllStudentsByYear()
    {
        $listEtudiant = $this->etudiantRepository->getAllStudentsByYear("2014-2015");
        $this->assertTrue(count($listEtudiant) == 49);
    }

    public function testGetHandicapStudentThisYear()
    {
        $infoHandicapEtu = $this->etudiantRepository->getHandicapStudentThisYear(62, "2013-2014");
        $this->assertEquals(108,$infoHandicapEtu[0]['id']);
    }

    public function testGetStudentsReinscription()
    {
        $listEtudiantReinscription = $this->etudiantRepository->getStudentsReinscription(2013);
        $this->assertEquals(12,count($listEtudiantReinscription));
    }

    public function testGetStudentByStudentHandicapId()
    {
        $student = $this->etudiantRepository->getStudentByStudentHandicapId(108);
        $this->assertEquals(62,$student[0]['id']);
    }

    public function testGetStudentInformationsByYear()
    {
        $studentInfo = $this->etudiantRepository->getStudentInformationsByYear(62, "2013-2014");
        $this->assertEquals("Ludovic",$studentInfo[0]['prenom']);
    }

        
    public function testGetStudentByStudentInformationsId()
    {
        $student = $this->etudiantRepository->getStudentByStudentInformationsId(83);
        $this->assertEquals(62,$student[0]->getId());
        //$this->assertTrue(count($student)==1);
    }
    
    public function testGetAllIdNameSurname()
    {
        $student = $this->etudiantRepository->getAllIdNameSurname(2012);
        $this->assertEquals(12,count($student));
    }
    
    public function testGetListeEtudiantsParNomOuPrenom()
    {
        $listEtudiant = $this->etudiantRepository->getListeEtudiantsParNomOuPrenom("Ba",2014);
        $this->assertEquals(3,count($listEtudiant));
    }
}