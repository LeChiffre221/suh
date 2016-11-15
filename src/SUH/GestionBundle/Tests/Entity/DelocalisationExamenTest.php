<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\DelocalisationExamen;

class DelocalisationExamenTest extends WebTestCase
{
    private $delocalisationExamen;

    protected function setUp()
    {
        $this->delocalisationExamen = new DelocalisationExamen("le lieu","un detail");
      

        $this->assertNotNull($this->delocalisationExamen);
    }

    public function testGetId()
    {
        $this->assertNull($this->delocalisationExamen->getId());
    }

    public function testGetLieu()
    {
        $this->assertEquals("le lieu", $this->delocalisationExamen->getLieu());
    }

    public function testSetLieu()
    {
        $this->delocalisationExamen->setLieu("autreLieu");
        $this->assertEquals("autreLieu", $this->delocalisationExamen->getLieu());
    }

    public function testGetDetail()
    {
        $this->assertEquals("un detail", $this->delocalisationExamen->getDetail());
    }

    public function testSetDetail()
    {
        $this->delocalisationExamen->setDetail("autreDetail");
        $this->assertEquals("autreDetail", $this->delocalisationExamen->getDetail());
    }

}
