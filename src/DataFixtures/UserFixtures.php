<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $userPasswordValidator)
    {
    }

    public function load(ObjectManager $manager): void
    {

        $user = new \App\Entity\User();
        $user->setEmail('admin@test.fr');
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $hashedPassword = $this->userPasswordValidator->hashPassword($user, 'admin');
        $user->setPassword($hashedPassword);
        $user->setIsVerified(true);
        $user->setUsername('admin');
        $user->setApiToken('admin_token_1234567890');
        $manager->persist($user);

        for ( $i = 0; $i < 10; $i++ ) {
            $user = new \App\Entity\User();
            $user->setEmail("user$i@test.fr");
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->userPasswordValidator->hashPassword($user, 'password');
                $user->setPassword($hashedPassword);
            $user->setIsVerified(true);
            $user->setUsername("user_$i");
            $user->setApiToken(bin2hex(random_bytes(30)));

            $this->addReference("USER_$i", $user);

            $manager->persist($user);
        }


        $manager->flush();
    }
}
