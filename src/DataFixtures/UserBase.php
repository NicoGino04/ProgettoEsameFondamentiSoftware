<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Goal;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserBase extends Fixture implements FixtureGroupInterface
{

    public function __construct (
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public static function getGroups(): array
    {
        return ['userBase'];
    }

    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setEmail("tu@tu.tu");
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, "tututu")
        );
        $user->setRoles(["ROLE_ADMIN"]);

       for ($i = 0; $i <= 49; ++$i) {

           $goal = new Goal();
           $goal->setName("Obiettivo $i");
           $goal->setExpiration(new \DateTime("2026-06-30"));
           $goal->setQuantity("$i");
           $goal->setGoalQuantity("100");

           $user->addGoal($goal);

       }

       $manager->persist($user);


        $manager->flush();
    }
}
