<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\Handicap;

class HandicapTest extends WebTestCase
{
    protected function setUp()
    {
        $this->handicap = new Handicap("handicap");
       
        $this->assertNotNull($this->handicap);

    }

    public function testGetId()
    {
        $this->assertNull($this->handicap->getId());
    }

    public function testGetNomHandicap()
    {
        $this->assertEquals("handicap",$this->handicap->getNomHandicap());
    }

    public function testSetNomHandicap()
    {
        $this->handicap->setNomHandicap("handicap2");
        $this->assertEquals("handicap2",$this->handicap->getNomHandicap());
    }

    

}
