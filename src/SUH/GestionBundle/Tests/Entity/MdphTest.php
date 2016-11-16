<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Mdph;

class MdphTest extends WebTestCase
{
    protected function setUp()
    {
        $this->mdph = new Mdph("departement");
       
        $this->assertNotNull($this->mdph);

    }

    public function testGetId()
    {
        $this->assertNull($this->mdph->getId());
    }

    public function testGetDepartementMdph()
    {
        $this->assertEquals("departement",$this->mdph->getDepartementMdph());
    }

    public function testSetDepartementMdph()
    {
        $this->mdph->setDepartementmdph("departement2");
        $this->assertEquals("departement2",$this->mdph->getDepartementMdph());
    }

    

}
