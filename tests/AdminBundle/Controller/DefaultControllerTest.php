<?php

namespace Tests\AdminBundle\Controller;

use Tests\TestBase;

class DefaultControllerTest extends TestBase
{
    public function testIndex()
    {
        $this->logInAdmin();

        $this->client->request('GET', '/admin/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /admin/".$this->client->getResponse()->getContent());
    }

    public function testRedirectToLoginIfNotAdmin()
    {

        $this->client->request('GET', '/admin/');

        $this->assertEquals(302, $this->client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /admin/".$this->client->getResponse()->getContent());
    }
}
