<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Goal;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

final class PrivateareaController extends AbstractController
{
    #[Route('/privatearea', name: 'app_privatearea')]
    public function index(): Response
    {

        $user = $this->getUser();
        //$goals = $user->getGoals()->slice(0,5);
        /* @var Goal $goal */
        //topGoals = obiettivi sopra il 35%
        $topGoals = $user->getGoals()->filter(static fn ($goal): bool => $goal->getPercentage() > 35);
        //bottomGoals = obiettivi sotto il 20
        $bottomGoals = $user->getGoals()->filter(static fn ($goal): bool => $goal->getPercentage() < 20);

        $topGoals = $topGoals->toArray();
        $bottomGoals = $bottomGoals->toArray();

        //ordinamento topGoals in descending
        usort($topGoals, function (Goal $a, Goal $b): int {
            $valA = $a->getPercentage();
            $valB = $b->getPercentage();
            return $valB - $valA;
        });

        //ordinamento bottomGoals in ascending
        usort($bottomGoals, function (Goal $a, Goal $b): int {
            $valA = $a->getPercentage();
            $valB = $b->getPercentage();
            return $valA - $valB;
        });

        //selezione esclusiva dei primi 5 elementi
        $topGoals = array_slice($topGoals, 0, 5);
        $bottomGoals = array_slice($bottomGoals, 0, 5);

        /*
        $criteria = Criteria::create()
            ->orderBy(['quantity' => Order::Descending]);
        $topGoals = $topGoals->matching($criteria);

        $criteria = Criteria::create()
            ->orderBy(['quantity' => Order::Ascending]);
        $bottomGoals = $bottomGoals->matching($criteria);
        */

        return $this->render('privatearea/index.html.twig', [
            'topGoals' => $topGoals,
            'bottomGoals' => $bottomGoals,
            'controller_name' => 'PrivateareaController',
        ]);
    }

    #[Route('/accountarea', name: 'account_page')]
    public function accountArea(): Response
    {
        return $this->render('privatearea/account.html.twig', [
            'controller_name' => 'PrivateareaController',
        ]);
    }

    #[Route('/graphicsarea', name: 'graphics_page')] //directory,nome
    public function graphicsArea(): Response
    {
        return $this->render('privatearea/graphics.html.twig', [
            'controller_name' => 'PrivateareaController',
        ]);
    }

    #[Route('/linegraphicsarea', name: 'line_graphics_page')] //directory,nome
    public function lineGraphicsArea(): Response
    {
        return $this->render('privatearea/linegraphics.html.twig', [
            'controller_name' => 'PrivateareaController',
        ]);
    }

}
