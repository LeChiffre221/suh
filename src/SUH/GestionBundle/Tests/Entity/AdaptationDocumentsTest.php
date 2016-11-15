<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\AdaptationDocuments;

class AdaptationDocumentsTest extends WebTestCase
{	

    protected function setUp()
    {
    	$detail="un detail";
    	
        $this->adapt = new AdaptationDocuments($detail);
		
		$this->assertNotNull($this->adapt);

    }

    public function testGetId()
    {
        $this->assertNull($this->adapt->getId());
    }

    public function testSetDetail()
    {
        $result=$this->adapt->setDetail('nouveauDetail');

        $this->assertEquals("nouveauDetail", $this->adapt->getDetail());
    }

    public function testGetDetail()
    {
        $this->assertEquals("un detail", $this->adapt->getDetail());
    }

}
