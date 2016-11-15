<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Materiel;

class MaterielTest extends WebTestCase
{
    protected function setUp()
    {
        $this->materiel = new Materiel("materiel");
       
        $this->assertNotNull($this->materiel);

    }

    public function testGetId()
    {
        $this->assertNull($this->materiel->getId());
    }

    public function testGetNom()
    {
        $this->assertEquals("materiel",$this->materiel->getNom());
    }

    public function testSetNom()
    {
        $this->materiel->setNom("materiel2");
        $this->assertEquals("materiel2",$this->materiel->getNom());
    }

    

}
