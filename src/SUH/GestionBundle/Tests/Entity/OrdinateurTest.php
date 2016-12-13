<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Ordinateur;

class OrdinateurTest extends WebTestCase
{
    protected function setUp()
    {
        $this->ordinateur = new Ordinateur("ordi");
       
        $this->assertNotNull($this->ordinateur);

    }

    public function testGetId()
    {
        $this->assertNull($this->ordinateur->getId());
    }

    public function testGetType()
    {
        $this->assertEquals("ordi",$this->ordinateur->getType());
    }

    public function testSetType()
    {
        $this->ordinateur->setType("ordi2");
        $this->assertEquals("ordi2",$this->ordinateur->getType());
    }

    

}
