<?php

namespace Tests\AdminBundle\Controller;

use Tests\TestBase;

class UserControllerTest extends TestBase
{
    public function testList()
    {
        $this->logInAdmin();

        $this->client->request('GET', '/admin/user/list');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /admin/".$this->client->getResponse()->getContent());
    }
}
