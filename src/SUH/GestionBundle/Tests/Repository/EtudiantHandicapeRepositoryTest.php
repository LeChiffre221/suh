<?php

namespace SUH\GestionBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\EtudiantHandicapeRepository;





class EtudiantHandicapeRepositoryTest extends WebTestCase
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
                                       ->getRepository('SUHGestionBundle:EtudiantHandicape');
    }


    public function testGetStudentConcerned()
    {
        $etudiant = $this->etudiantRepository->getStudentConcerned(108);
       
        $this->assertTrue($etudiant->getId() == 62);
    }
    
    public function testGetInformationsStudent()
    {
        $infoEtudiant = $this->etudiantRepository->getInformationsStudent(108,"2013-2014");
       
        $this->assertEquals("Rabat",$infoEtudiant[0]->getEtudiantInformations()->getNom());
    }

    public function testGetModifInformationsStudent()  
    {
        $infoEtudiant = $this->etudiantRepository->getModifInformationsStudent(62,"2013-2014",true);
       
        $this->assertEquals("AJXQS",$infoEtudiant->getNumeroEtudiant());
    }

    public function testGetLastsInformationsStudent()  
    {
        $infoEtudiant = $this->etudiantRepository->getLastsInformationsStudent(62);
       
        $this->assertEquals("Ludovic",$infoEtudiant->getListEtudiantInformations()[0]->getEtudiantInformations()->getPrenom());
    }  
    

       
}
