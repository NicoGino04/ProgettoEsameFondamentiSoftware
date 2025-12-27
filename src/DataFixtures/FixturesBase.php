<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Goal;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FixturesBase extends Fixture implements FixtureGroupInterface
{

    public function __construct (
        private readonly UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public static function getGroups(): array
    {
        return ['datiBase'];
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("tu@tu.tu");
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, "tututu")
        );
        $user->setRoles(["ROLE_ADMIN"]);

        $goal = new Goal();
        $goal->setName("Bicchieri d'acqua");
        $goal->setExpiration(new \DateTime("2026-06-30"));
        $goal->setQuantity("3");
        $goal->setGoalQuantity("100");

        $user->addGoal($goal);

        $goal = new Goal();
        $goal->setName("Micronutrienti assunti");
        $goal->setExpiration(new \DateTime("2026-06-30"));
        $goal->setQuantity("6");
        $goal->setGoalQuantity("600");

        $user->addGoal($goal);

        $manager->persist($user);

        $user = new User();
        $user->setEmail("io@io.io");
        $user->setPassword(
            $this->userPasswordHasher->hashPassword($user, "ioioio")
        );
        $user->setRoles(["ROLE_USER"]);

        $goal = new Goal();
        $goal->setName("Bicchieri d'acqua");
        $goal->setExpiration(new \DateTime("2026-06-30"));
        $goal->setQuantity("3");
        $goal->setGoalQuantity("100");

        $user->addGoal($goal);

        $goal = new Goal();
        $goal->setName("Macronutrienti assunti");
        $goal->setExpiration(new \DateTime("2026-06-30"));
        $goal->setQuantity("6");
        $goal->setGoalQuantity("600");

        $user->addGoal($goal);

        $manager->persist($user);

        $manager->flush();
    }
}
