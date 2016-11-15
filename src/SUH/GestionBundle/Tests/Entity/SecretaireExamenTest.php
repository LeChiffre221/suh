<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\SecretaireExamen;

class SecretaireExamenTest extends WebTestCase
{
    protected function setUp()
    {
        $this->secretaireExamen = new SecretaireExamen("secretaire");
       
        $this->assertNotNull($this->secretaireExamen);

    }

    public function testGetId()
    {
        $this->assertNull($this->secretaireExamen->getId());
    }

    public function testGetFonction()
    {
        $this->assertEquals("secretaire",$this->secretaireExamen->getFonction());
    }

    public function testSetFonction()
    {
        $this->secretaireExamen->setFonction("ordi2");
        $this->assertEquals("ordi2",$this->secretaireExamen->getFonction());
    }

    

}
