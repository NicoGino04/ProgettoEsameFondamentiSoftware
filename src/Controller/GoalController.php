<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Goal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use App\Form\GoalType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class GoalController extends AbstractController
{
    #[Route('/goals', name: 'goal_index')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error){
            return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            ]);
        }

        $user = $this->getUser();
        $goals = $user->getGoals();
        return $this->render('goal/index.html.twig', [
            'goals' => $goals,
        ]);
        
    }

    #[Route('/goal/{id<\d+>}',name:'goal_show')]
    public function show(Goal $goal): Response
    {
        return $this->render('goal/show.html.twig', [
            'goal'=> $goal
        ]);
    }

    #[Route('/goal/new', name:'goal_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $goal = new Goal();

        $form = $this->createForm(GoalType::class, $goal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $user = $this->getUser();
            $user->addGoal($goal);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'notice',
                'Goal created successfully!'
            );

            return $this->redirectToRoute('goal_show', [
                'id'=> $goal->getId()
            ]);
        }

        return $this->render('goal/new.html.twig',[
            'form'=> $form,
        ]);
    }

    #[Route('/goal/{id<\d+>}/edit', name:'goal_edit')]
    public function edit(Goal $goal,Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(GoalType::class, $goal);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager->flush();

            $this->addFlash(
                'notice',
                'Product updated successfully!'
            );

            return $this->redirectToRoute('goal_show', [
                'id'=> $goal->getId()
            ]);
        }

        return $this->render('goal/edit.html.twig',[
            'form'=> $form,
        ]);
    }

    #[Route('/goal/{id<\d+>}/delete', name:'goal_delete')]
    public function delete(Goal $goal, Request $request, EntityManagerInterface $manager): Response
    {
        if ($request->isMethod('POST')) {

            $manager->remove($goal);

            $manager->flush();

            $this->addFlash(
                'notice',
                'Product deleted successfully!'
            );

            return $this->redirectToRoute('goal_index');

        }

        return $this->render('goal/delete.html.twig', [
            'id' => $goal->getId(),
        ]);
    }
}
