<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SeanceCoachingController extends AbstractController
{
    #[Route('/seance/coaching', name: 'app_seance_coaching')]
    public function index(): Response
    {
        return $this->render('seance_coaching/index.html.twig', [
            'controller_name' => 'SeanceCoachingController',
        ]);
    }
}
