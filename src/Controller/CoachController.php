<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\CoursRepository;
use App\Repository\SeanceCoachingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/coach')]
#[IsGranted('ROLE_COACH')]
class CoachController extends AbstractController
{
    #[Route('/', name: 'coach_dashboard')]
    public function dashboard(
        ClientRepository $clientRepo,
        CoursRepository $coursRepo,
        SeanceCoachingRepository $seanceRepo
    ): Response {
        $coach = $this->getUser();

        // Statistiques pour le coach
        $stats = [
            'mes_clients' => $clientRepo->count([]),
            'cours_aujourdhui' => $coursRepo->count(['date' => new \DateTime()]),
            'seances_prevues' => $seanceRepo->count(['coach' => $coach, 'statut' => 'prevue']),
            'seances_completees' => $seanceRepo->count(['coach' => $coach, 'statut' => 'completee']),
        ];

        // Prochaines séances
        $prochaines_seances = $seanceRepo->findBy(
            ['coach' => $coach, 'statut' => 'prevue'],
            ['dateHeure' => 'ASC'],
            5
        );

        return $this->render('coach/dashboard.html.twig', [
            'coach' => $coach,
            'stats' => $stats,
            'prochaines_seances' => $prochaines_seances,
        ]);
    }

    #[Route('/planning', name: 'coach_planning')]
    public function planning(): Response
    {
        return $this->render('coach/planning.html.twig');
    }

    #[Route('/clients', name: 'coach_clients')]
    public function clients(ClientRepository $clientRepo): Response
    {
        // Les clients assignés à ce coach (vous devrez adapter selon votre logique)
        $clients = $clientRepo->findAll();

        return $this->render('coach/clients.html.twig', [
            'clients' => $clients,
        ]);
    }
}
