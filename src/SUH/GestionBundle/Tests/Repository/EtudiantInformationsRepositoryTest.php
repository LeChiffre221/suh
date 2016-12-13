<?php

namespace SUH\GestionBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\EtudiantEtudiantInformations;
use SUH\GestionBundle\Entity\EtudiantInformationsRepository;





class EtudiantInformationsRepositoryTest extends WebTestCase
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
                                       ->getRepository('SUHGestionBundle:EtudiantInformations');
    }

    public function testGetStudentByStudentInformationsId()
    {
        $listEtudiant = $this->etudiantRepository->getStudentByStudentInformationsId(86);
        
        $this->assertEquals(65,$listEtudiant[0]->getId());

        
        
    }

   
}