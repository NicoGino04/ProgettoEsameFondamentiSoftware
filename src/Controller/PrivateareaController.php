<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\Dato;
use App\Entity\Pasto;
use App\Entity\User;
use App\Entity\Goal;
use App\Repository\ActivityRepository;
use App\Repository\PastoRepository;
use App\Repository\DatoRepository;
use Doctrine\Common\Collections\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class PrivateareaController extends AbstractController
{
    public function __construct(private readonly PastoRepository $pastoRepository, private readonly EntityManagerInterface $entityManager,
                                private readonly ActivityRepository $activityRepository, private readonly DatoRepository $datoRepository)
    {
    }

    #[Route('/privatearea', name: 'app_privatearea')]
    public function index(): Response
    {

        $user = $this->getUser();
        //$goals = $user->getGoals()->slice(0,5);
        /* @var Goal $goal */
        //topGoals = obiettivi sopra il 35%
        $topGoals = $user->getGoals()->filter(static fn ($goal): bool => ($goal->getPercentage() > 35 && $goal->getPercentage() <100));
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

    #[Route('/attivita/create', name: 'attivita_create', methods: ['POST'])]
    public function createActivity(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$this->getUser()){
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Utente non loggato'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        if($this->getUser()->getBasale() != null) {
            $attivita = new Activity();
            $attivita->setTipo($data['tipo']); // normale, grassa, ecc
            $attivita->setData();        // data automatica
            $attivita->setUser($this->getUser());
            $attivita->setMinutiAtt($data['minutiAttivita'] * 10);

            $em->persist($attivita);
            $em->flush();

            $calorie = $this->getCalorie();

            return new JsonResponse([
                'status' => 'ok',
                'id' => $attivita->getId(),
                'carboidrati' => $calorie['carboidrati'],
                'grassi' => $calorie['grassi'],
                'proteine' => $calorie['proteine'],
            ]);
        }
        else{
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Prima di poter inserire pasti o attività inserisci i tuoi parametri nella sezione Account/EmailAccount'
            ], Response::HTTP_UNAUTHORIZED);
        }

    }

    #[Route('/account/create', name: 'account_create', methods: ['POST'])]
    public function createAccount(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$this->getUser()){
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Utente non loggato'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        $account = $this->getUser();
        $account->setAltezza($data['altezza']);
        $account->setEtà($data['eta']);
        $account->setPeso($data['peso']);
        $account->setSesso($data['sesso']);
        $account->setBasale();

        $em->persist($account);
        $em->flush();

        return new JsonResponse([
            'status' => 'ok',
            'id' => $account->getId()
        ]);
    }

    #[Route('/obiettivo/create', name: 'obiettivo_create', methods: ['POST'])]
    public function createGoal(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$this->getUser()){
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Utente non loggato'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);
        $rightDate = new \DateTime($data['dataGoal']);

        $goal = new Goal();
        $goal->setName($data['tipoGoal']);
        $goal->setExpiration($rightDate);
        $goal->setUser($this->getUser());
        $goal->setGoalQuantity($data['quantitaGoal']);

        $em->persist($goal);
        $em->flush();

        return new JsonResponse([
            'status' => 'ok',
            'id' => $goal->getId()
        ]);
    }

    #[Route('/linegraphicsarea', name: 'line_graphics_page')] //directory,nome
    public function lineGraphicsArea(): Response
    {
        return $this->render('privatearea/linegraphics.html.twig', [
            'controller_name' => 'PrivateareaController',
        ]);
    }

    #[Route('/obbiettiviarea', name: 'obbiettivi_area')] //directory,nome
    public function obbiettiviArea(): Response
    {
        return $this->render('privatearea/obbiettivi.html.twig', [
            'controller_name' => 'PrivateareaController',
        ]);
    }

    #[Route('/pasto/create', name: 'pasto_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$this->getUser()){
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Utente non loggato'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        if($this->getUser()->getBasale() != null) {
            $pasto = new Pasto();
            $pasto->setPasto($data['pasto']);      // colazione, pranzo, cena
            $pasto->setTipo($data['tipo']); // normale, grassa, ecc
            $pasto->setGiorno();        // data automatica
            $pasto->setUser($this->getUser());

            $em->persist($pasto);
            $em->flush();

            $calorie = $this->getCalorie();

            return new JsonResponse([
                'status' => 'ok',
                'id' => $pasto->getId(),
                'carboidrati' => $calorie['carboidrati'],
                'grassi' => $calorie['grassi'],
                'proteine' => $calorie['proteine'],
            ]);
        }
        else{
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Prima di poter inserire pasti o attività inserisci i tuoi parametri nella sezione Account/EmailAccount'
            ], Response::HTTP_UNAUTHORIZED);
        }


    }

    private function getCalorie(): array{

        $user = $this->getUser();
        $calorieAttivita = 0;

        $attivitaGiornaliere = $this->activityRepository->findByDateField($user);

        foreach ($attivitaGiornaliere as $attivita){

            switch ($attivita->getTipo()){
                case 'nuoto': $met = 6.0; break;
                case 'camminata': $met = 3.5; break;
                case 'corsa': $met = 8.0; break;
                case 'basket': $met = 8.0; break;
                case 'pallavolo': $met = 4.0; break;
                case 'calcio': $met = 7.0; break;
                case 'tennis': $met = 7.3; break;
                case 'padel': $met = 6.0; break;
                case 'pallamano': $met = 8.0; break;
            }

            $durataOre = $attivita->getMinutiAtt();
            $calorieAttivita += ($met * $user->getPeso() * $durataOre);

        }

        $valoreGiornaliero = $user->getBasale() + $calorieAttivita;

        $pastiGiornalieri = $this->pastoRepository->findByDateField($user);

        $kcal_carbo = 0;
        $kcal_grassi = 0;
        $kcal_proteine = 0;

        foreach ($pastiGiornalieri as $pasto){

            switch ($pasto->getPasto()){
                case 'colazione' : $percPasto = 25; break;
                case 'pranzo' : $percPasto = 40; break;
                case 'cena' : $percPasto = 35; break;
                default : $percPasto = 0;
            }

            switch ($pasto->getTipo()){
                case 'normale' :
                case 'leggera' :
                case 'abbondante' : $percCarbo = 58; $percGrassi = 26; $percProteine = 16; break;
                case 'proteico' : $percCarbo = 25; $percGrassi = 30; $percProteine = 45; break;
                case 'grassa' : $percCarbo = 20; $percGrassi = 55; $percProteine = 25; break;
                case 'light' : $percCarbo = 40; $percGrassi = 35; $percProteine = 25; break;
                default : $percCarbo = 0; $percGrassi = 0; $percProteine = 0; break;
            }

            $kcal = ($valoreGiornaliero * $percPasto) / 100;
            $kcal_carbo = $kcal_carbo + ($kcal * $percCarbo) / 100;
            // $grammi_carbo = $kcal_carbo_colazione/4;
            $kcal_grassi = $kcal_grassi + ($kcal * $percGrassi) / 100;
            $kcal_proteine = $kcal_proteine + ($kcal * $percProteine) / 100;

        }
        return [
            'carboidrati' => $kcal_carbo,
            'grassi' => $kcal_grassi,
            'proteine' => $kcal_proteine
        ];
    }

    #[Route('/macronutrienti', name: 'macronutrienti', methods: ['GET'])]
    public function macronutrienti(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$this->getUser()){
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Utente non loggato'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $calorie = $this->getCalorie();

        return new JsonResponse([
            'status' => 'ok',
            'carboidrati' => $calorie['carboidrati'],
            'grassi' => $calorie['grassi'],
            'proteine' => $calorie['proteine'],
        ]);
    }

    #[Route('/splinechart', name: 'lineChart', methods: ['GET'])]
    public function lineChart(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$this->getUser()){
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Utente non loggato'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = array_map(fn($row) => [
            'data' => $row['data']->format('Y-m-d'), // ISO
            'totale' => (int) $row['totale']
        ], $this->datoRepository->getGoalsGroupedByDate($this->getUser()));

        return new JsonResponse([
            'status' => 'ok',
            'data' => $data
        ]);
    }

    #[Route('/increment', name: 'goal_increment', methods: ['POST'])]
    public function increment(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        if (!$this->getUser()){
            return new JsonResponse([
                'status' => 'error',
                'error' => 'Utente non loggato'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($request->getContent(), true);

        $user = $this->getUser();
        $goals = $user->getGoals();
        $saved = null;
        $dati = $user->getDati();

        if($goals->isEmpty()){

            if($dati->isEmpty()){
                $datoAcqua = new Dato();
                $datoAcqua->setUser($user);
                if($data['buttonType'] == "waterButton"){
                    $datoAcqua->setQuantità($data['trueCount']);
                }
                else{
                    $datoAcqua->setQuantità(0);
                }
                $datoAcqua->setData();
                $datoAcqua->setTipo("acqua");
                $em->persist($datoAcqua);

                $datoSonno = new Dato();
                $datoSonno->setUser($user);
                if($data['buttonType'] == "sleepButton"){
                    $datoSonno->setQuantità($data['trueCount']);
                }
                else{
                    $datoSonno->setQuantità(0);
                }
                $datoSonno->setData();
                $datoSonno->setTipo("sonno");
                $em->persist($datoSonno);

                $datoSole = new Dato();
                $datoSole->setUser($user);
                if($data['buttonType'] == "sunButton"){
                    $datoSole->setQuantità($data['trueCount']);
                }
                else{
                    $datoSole->setQuantità(0);
                }
                $datoSole->setData();
                $datoSole->setTipo("sole");
                $em->persist($datoSole);

                $em->flush();

            }

            if(!$dati->isEmpty()){

                $presenteAcqua = false;

                for($i = 0; $i < count($dati); $i++){

                    if($dati[$i]->getTipo() == "acqua" && $data['buttonType'] == "waterButton" && $dati[$i]->getData()->format('Y-m-d') === (new \DateTime())->format('Y-m-d')){
                        $dati[$i]->setQuantità($dati[$i]->getQuantità() + $data['trueCount']);
                    }
                    else if($dati[$i]->getTipo() == "acqua" && $data['buttonType'] == "waterButton" && $dati[$i]->getData()->format('Y-m-d') === (new \DateTime('-1 day'))->format('Y-m-d')){
                        $presenteAcqua = true;
                    }

                }

                if($presenteAcqua){
                    $datoAcqua = new Dato();
                    $datoAcqua->setUser($user);
                    $datoAcqua->setQuantità($data['trueCount']);
                    $datoAcqua->setData();
                    $datoAcqua->setTipo("acqua");
                    $em->persist($datoAcqua);
                }

                $em->flush();

            }

            return new JsonResponse([
                'status' => 'ok',
            ]);

        }
        else {

            if($dati->isEmpty()){

                $datoAcqua = new Dato();
                $datoAcqua->setUser($user);
                if($data['buttonType'] == "waterButton"){
                    $datoAcqua->setQuantità($data['trueCount']);
                }
                else{
                    $datoAcqua->setQuantità(0);
                }
                $datoAcqua->setData();
                $datoAcqua->setTipo("acqua");
                $em->persist($datoAcqua);

                $datoSonno = new Dato();
                $datoSonno->setUser($user);
                if($data['buttonType'] == "sleepButton"){
                    $datoSonno->setQuantità($data['trueCount']);
                }
                else{
                    $datoSonno->setQuantità(0);
                }
                $datoSonno->setData();
                $datoSonno->setTipo("sonno");
                $em->persist($datoSonno);

                $datoSole = new Dato();
                $datoSole->setUser($user);
                if($data['buttonType'] == "sunButton"){
                    $datoSole->setQuantità($data['trueCount']);
                }
                else{
                    $datoSole->setQuantità(0);
                }
                $datoSole->setData();
                $datoSole->setTipo("sole");
                $em->persist($datoSole);

                $em->flush();

            }

            if(!$dati->isEmpty()){

                $presente = false;

                for($i = 0; $i < count($dati); $i++){

                    if($dati[$i]->getTipo() == "acqua" && $data['buttonType'] == "waterButton" && $dati[$i]->getData()->format('Y-m-d') === (new \DateTime())->format('Y-m-d')){
                        $dati[$i]->setQuantità($dati[$i]->getQuantità() + $data['trueCount']);
                    }
                    else if($dati[$i]->getTipo() == "acqua" && $data['buttonType'] == "waterButton" && $dati[$i]->getData()->format('Y-m-d') === (new \DateTime('-1 day'))->format('Y-m-d')){
                        $presente = true;
                    }

                }

                if($presente){
                    $datoAcqua = new Dato();
                    $datoAcqua->setUser($user);
                    $datoAcqua->setQuantità($data['trueCount']);
                    $datoAcqua->setData();
                    $datoAcqua->setTipo("acqua");
                    $em->persist($datoAcqua);
                }

            }

            for ($i = 0; $i < count($goals); $i++) {
                if ($goals[$i]->getName() == "acqua" && $data['buttonType'] == "waterButton") {
                    $goals[$i]->setQuantity($goals[$i]->getQuantity() + $data['trueCount']);
                    $em->persist($goals[$i]);
                    $saved = $i;
                } else if ($goals[$i]->getName() == "sonno" && $data['buttonType'] == "sleepButton") {
                    $goals[$i]->setQuantity($goals[$i]->getQuantity() + $data['trueCount']);
                    $em->persist($goals[$i]);
                    $saved = $i;
                } else if ($goals[$i]->getName() == "sole" && $data['buttonType'] == "sunButton") {
                    $goals[$i]->setQuantity($goals[$i]->getQuantity() + $data['trueCount']);
                    $em->persist($goals[$i]);
                    $saved = $i;
                }
            }

            $em->flush();

            if (is_null($saved)) {
                return new JsonResponse([
                    'status' => 'error',
                    'error' => 'Obiettivo non corretto'
                ], Response::HTTP_BAD_REQUEST);
            }

            $completed = false;

            if ($goals[$saved]->getQuantity() >= $goals[$saved]->getGoalQuantity()) {
                $completed = true;
            }

            return new JsonResponse([
                'status' => 'ok',
                'id' => $goals[$saved]->getId(),
                'completed' => $completed,
                'percentage' => $goals[$saved]->getPercentage()
            ]);
        }
    }

}
