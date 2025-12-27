<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Goal;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("tu@tu.tu");
        $user->setPassword("tututu");
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
        $user->setPassword("ioioio");
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
