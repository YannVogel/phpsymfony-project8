<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends AbstractTestController
{
    public function getUserData(): array
    {
        return [
            [
                [
                    'username' => 'user ' . uniqid(),
                    'password' => 'password',
                    'email' => 'test.user@email.com',
                    'roles' => [],
                ]
            ],
            [
                [
                    'username' => 'admin ' .uniqid(),
                    'password' => 'password',
                    'email' => 'test.admin@email.com',
                    'roles' => [User::ROLE_ADMIN],
                ]
            ],
        ];
    }

    /**
     * @dataProvider getUserData
     */
    public function testCreateUser(array $userData)
    {
        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('user_create')
        );

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]'] = $userData['username'];
        $form['user[password][first]'] = $userData['password'];
        $form['user[password][second]'] = $userData['password'];
        $form['user[email]'] = $userData['email'];
        $form['user[roles]'] = $userData['roles'];

        $this->client->submit($form);

        $user = $this->userRepository->findByUsername($userData['username']);

        $this->assertCount(1, $user);
    }


    /**
     * @dataProvider getUserData
     */
    public function testEditUser(array $userData)
    {
        $this->connectAdmin();

        $user = $this->userRepository->findOneBy([]);
        $oldUsername = $user->getUsername();

        $crawler = $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('user_edit', ['id' => $user->getId()])
        );

        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = $userData['username'];
        $form['user[password][first]'] = $userData['password'];
        $form['user[password][second]'] = $userData['password'];

        $this->client->submit($form);

        $editedUser = $this->userRepository->findByUsername($userData['username']);
        $oldUser = $this->userRepository->findByUsername($oldUsername);

        $this->assertCount(1, $editedUser);
        $this->assertCount(0, $oldUser);
    }

    public function testAccessUsersListGranted()
    {
        $this->connectAdmin();

        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('user_list')
        );
        $this->assertEquals(
            Response::HTTP_OK ,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testAccessUsersListDenied()
    {
        $this->connectUser();

        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('user_list')
        );
        $this->assertEquals(
            Response::HTTP_FORBIDDEN ,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testAccessUsersEditingGranted()
    {
        $this->connectAdmin();

        $user = $this->userRepository->findOneBy([]);

        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('user_edit', ['id' => $user->getId()])
        );

        $this->assertEquals(
            Response::HTTP_OK ,
            $this->client->getResponse()->getStatusCode()
        );
    }

    public function testAccessUsersEditingDenied()
    {
        $this->connectUser();

        $user = $this->userRepository->findOneBy([]);

        $this->client->request(
            Request::METHOD_GET,
            $this->urlGenerator->generate('user_edit', ['id' => $user->getId()])
        );

        $this->assertEquals(
            Response::HTTP_FORBIDDEN ,
            $this->client->getResponse()->getStatusCode()
        );
    }
}
