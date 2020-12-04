<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\AdminUser;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new AdminUser();
        $user->setUsername('DefaultUser');
        $user->setEmail('Default@mail.com');
        $user->setPlainPassword('default');
        $manager->persist($user);
        $manager->flush();
    }
}
