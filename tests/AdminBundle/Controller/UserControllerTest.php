<?php

namespace Tests\AdminBundle\Controller;

use Tests\TestBase;

class UserControllerTest extends TestBase
{
    public function testIndex()
    {
        $this->logInAdmin();

        $this->client->request('GET', '/admin/user/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /admin/".$this->client->getResponse()->getContent());
    }
}
