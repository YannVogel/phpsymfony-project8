<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testEveryUsersHaveUserRole()
    {
        $user = new User();
        $user2 = new User();
        $user2->setRoles([User::ROLE_ADMIN]);

        $this->assertContains(User::ROLE_USER, $user->getRoles());
        $this->assertContains(User::ROLE_USER, $user2->getRoles());
    }
}
