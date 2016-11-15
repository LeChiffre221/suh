<?php

namespace SUH\GestionBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use SUH\GestionBundle\Entity\NotificationSavs;

class NotificationSavsTest extends WebTestCase
{
    protected function setUp()
    {
        $this->notificationSavs = new NotificationSavs("notif");
       
        $this->assertNotNull($this->notificationSavs);

    }

    public function testGetId()
    {
        $this->assertNull($this->notificationSavs->getId());
    }

    public function testGetNotificationText()
    {
        $this->assertEquals("notif",$this->notificationSavs->getNotificationText());
    }

    public function testSetNotificationText()
    {
        $this->notificationSavs->setNotificationText("notif2");
        $this->assertEquals("notif2",$this->notificationSavs->getNotificationText());
    }

    

}
