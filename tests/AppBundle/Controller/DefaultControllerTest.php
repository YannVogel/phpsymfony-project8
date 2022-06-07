<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends AbstractTestController
{
    public function testIndex()
    {
        $this->client->request('GET', $this->urlGenerator->generate('login'));
        $this->assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode()
        );
    }
}
