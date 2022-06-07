<?php

namespace Tests\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class SecurityControllerTest extends AbstractTestController
{
    const LOGIN_BUTTON_TEXT = "Se connecter";
    const LOGOUT_BUTTON_TEXT = "Se déconnecter";

    public function testLoginUser()
    {
        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('login')
        );

        $this->assertStringContainsString(
            self::LOGIN_BUTTON_TEXT,
            $this->client->getResponse()->getContent()
        );

        $this->connectUser();

        $this->assertStringContainsString(
            self::LOGOUT_BUTTON_TEXT,
            $this->client->getResponse()->getContent()
        );
    }
}
