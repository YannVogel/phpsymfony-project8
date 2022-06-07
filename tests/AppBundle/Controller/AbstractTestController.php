<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\AppFixtures;
use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use AppBundle\Repository\TaskRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTestController extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Router
     */
    protected $urlGenerator;

    /**
     * @var EntityRepository
     */
    protected $userRepository;

    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * @var User
     */
    protected $user;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router');
        $this->userRepository = $this->client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(User::class);
        $this->taskRepository = $this->client
            ->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Task::class);
    }

    public function tearDown(): void
    {
        $this->client = null;
        $this->urlGenerator = null;
        $this->userRepository = null;
        $this->taskRepository = null;
        $this->user = null;
    }

    public function connectAdmin()
    {
        $this->connect(true);
    }

    public function connectUser()
    {
        $this->connect();
    }

    /**
     * @param bool $admin
     * @return void
     */
    private function connect(bool $admin = false)
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('login'));

        $form = $crawler->selectButton('Se connecter')->form();
        $form['_username'] = $admin ?
            AppFixtures::TEST_ADMIN_USERNAME :
            AppFixtures::TEST_USER_USERNAME;
        $form['_password'] = $admin ?
            AppFixtures::TEST_ADMIN_PASSWORD :
            AppFixtures::TEST_USER_PASSWORD;

        $this->client->submit($form);
        $this->client->followRedirect();

        $this->user = $this->userRepository->findOneByUsername(
            $admin ?
                AppFixtures::TEST_ADMIN_USERNAME :
                AppFixtures::TEST_USER_USERNAME
        );
    }
}
