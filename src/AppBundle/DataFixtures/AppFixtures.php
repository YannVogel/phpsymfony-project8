<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface
{
    const TEST_ADMIN_USERNAME = 'root';
    const TEST_ADMIN_PASSWORD = 'root';
    const TEST_USER_USERNAME = 'user';
    const TEST_USER_PASSWORD = 'password';

    const MIN_USER_ROLE = 2;
    const MAX_USER_ROLE = 6;

    const MIN_TASK = 2;
    const MAX_TASK = 8;

    /**
     * @var Generator
     */
    private $faker;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var object
     */
    private $encoder;


    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * @param ContainerInterface|null $container
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $this->encoder = $this->container->get('security.password_encoder');

        $testAdmin = $this->createTestAdmin();
        $manager->persist($testAdmin);

        $testUser = $this->createTestUser();
        $manager->persist($testUser);

        foreach ($this->createTasks($testAdmin) as $testAdminTask) {
            $manager->persist($testAdminTask);
        }

        foreach ($this->createTasks($testUser) as $testUserTask) {
            $manager->persist($testUserTask);
        }

        foreach ($this->createRandomUsers() as $randomUser) {
            $manager->persist($randomUser);

            foreach ($this->createTasks($randomUser) as $task) {
                $manager->persist($task);
            }
        }

        foreach ($this->createTasks() as $anonTask) {
            $manager->persist($anonTask);
        }

        $manager->flush();
    }

    /**
     * @return \Generator
     */
    private function createRandomUsers()
    {
        $number = mt_rand(self::MIN_USER_ROLE, self::MAX_USER_ROLE);

        for ($i = 0; $i < $number; $i++) {
            $user = new User();
            $user->setUsername($this->faker->userName);
            $user->setEmail($this->faker->email);
            $user->setPassword($this->encoder->encodePassword($user, $this->faker->password));
            $user->setRoles([User::ROLE_USER]);

            yield $user;
        }
    }

    /**
     * @param $user
     * @return \Generator
     */
    private function createTasks($user = null)
    {
        $taskNumber = mt_rand(self::MIN_TASK, self::MAX_TASK);

        for ($i = 0; $i < $taskNumber; $i++) {
            $task = new Task();
            $task->setUser($user);
            $task->setContent($this->faker->text(50));
            $task->setCreatedAt($this->faker->dateTime);
            $task->setTitle($this->faker->sentence(3, false));
            $task->toggle(mt_rand(0, 1));

            yield $task;
        }
    }

    /**
     * @return User
     */
    private function createTestAdmin()
    {
        $admin = new User();
        $admin->setUsername(self::TEST_ADMIN_USERNAME);
        $admin->setEmail($this->faker->email);
        $admin->setPassword($this->encoder->encodePassword($admin, self::TEST_ADMIN_PASSWORD));
        $admin->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);

        return $admin;
    }

    /**
     * @return User
     */
    private function createTestUser()
    {
        $user = new User();
        $user->setUsername(self::TEST_USER_USERNAME);
        $user->setEmail($this->faker->email);
        $user->setPassword($this->encoder->encodePassword($user, self::TEST_USER_PASSWORD));
        $user->setRoles([User::ROLE_USER]);

        return $user;
    }
}
