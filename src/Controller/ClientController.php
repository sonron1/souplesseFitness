<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'client_dashboard')]
    public function dashboard(
        AbonnementRepository $abonnementRepo,
        CoursRepository $coursRepo,
        ClientRepository $clientRepo
    ): Response {
        $user = $this->getUser();

        // Récupérer les informations du client
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);
        $abonnements = $abonnementRepo->findBy(['actif' => true]);

        // Cours disponibles
        $cours_disponibles = $coursRepo->findBy(
            ['actif' => true],
            ['dateHeure' => 'ASC'],
            5
        );

        // Vérifier si le client a un abonnement actif
        $abonnementActif = $client && $client->getAbonnementActuel() &&
            $client->getDateFinAbonnement() &&
            $client->getDateFinAbonnement() > new \DateTime();

        $stats = [
            'abonnement_actif' => $abonnementActif,
            'cours_reserves' => $client ? $client->getCoursInscrits()->count() : 0,
            'seances_restantes' => $this->calculerSeancesRestantes($client),
            'points_fidelite' => $client ? $client->getPointsFidelite() : 0,
        ];

        return $this->render('client/dashboard.html.twig', [
            'user' => $user,
            'client' => $client,
            'stats' => $stats,
            'abonnements' => $abonnements,
            'cours_disponibles' => $cours_disponibles,
            'abonnement_actif' => $abonnementActif,
        ]);
    }

    #[Route('/profil', name: 'client_profil')]
    public function profil(ClientRepository $clientRepo): Response
    {
        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);

        return $this->render('client/profil.html.twig', [
            'user' => $user,
            'client' => $client,
        ]);
    }

    #[Route('/abonnements', name: 'client_abonnements')]
    public function abonnements(
        AbonnementRepository $abonnementRepo,
        ClientRepository $clientRepo
    ): Response {
        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);
        $abonnements = $abonnementRepo->findBy(['actif' => true], ['categorie' => 'ASC', 'prix' => 'ASC']);

        // Organiser les abonnements par catégories
        $abonnementsParCategorie = [
            'simple' => [],
            'couple' => [],
            'promo' => []
        ];

        foreach ($abonnements as $abonnement) {
            $categorie = $abonnement->getCategorie() ?? 'simple';

            if ($abonnement->isEnPromotion() && $abonnement->isPromotionActive()) {
                $abonnementsParCategorie['promo'][] = $abonnement;
            } elseif (str_contains(strtolower($abonnement->getNom()), 'couple') || $categorie === 'couple') {
                $abonnementsParCategorie['couple'][] = $abonnement;
            } else {
                $abonnementsParCategorie['simple'][] = $abonnement;
            }
        }

        return $this->render('client/abonnements.html.twig', [
            'user' => $user,
            'client' => $client,
            'abonnements' => $abonnements,
            'abonnementsParCategorie' => $abonnementsParCategorie,
        ]);
    }

    #[Route('/reservations', name: 'client_reservations')]
    public function reservations(
        CoursRepository $coursRepo,
        ClientRepository $clientRepo
    ): Response {
        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);

        // Vérifier si le client a un abonnement actif
        $abonnementActif = $client && $client->getAbonnementActuel() &&
            $client->getDateFinAbonnement() &&
            $client->getDateFinAbonnement() > new \DateTime();

        if (!$abonnementActif) {
            $this->addFlash('warning', 'Vous devez avoir un abonnement actif pour réserver des cours.');
            return $this->redirectToRoute('client_abonnements');
        }

        // Cours disponibles pour réservation
        $cours_disponibles = $coursRepo->createQueryBuilder('c')
            ->where('c.actif = :actif')
            ->andWhere('c.dateHeure > :now')
            ->setParameter('actif', true)
            ->setParameter('now', new \DateTime())
            ->orderBy('c.dateHeure', 'ASC')
            ->getQuery()
            ->getResult();

        // Cours réservés par le client
        $cours_reserves = $client ? $client->getCoursInscrits() : [];

        return $this->render('client/reservations.html.twig', [
            'user' => $user,
            'client' => $client,
            'cours_disponibles' => $cours_disponibles,
            'cours_reserves' => $cours_reserves,
        ]);
    }

    #[Route('/souscrire/{id}', name: 'client_souscrire_abonnement', methods: ['POST'])]
    public function souscrireAbonnement(
        int $id,
        Request $request,
        AbonnementRepository $abonnementRepo,
        ClientRepository $clientRepo,
        EntityManagerInterface $entityManager
    ): Response {
        // Vérification du token CSRF
        if (!$this->isCsrfTokenValid('subscribe_abonnement', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token de sécurité invalide.');
            return $this->redirectToRoute('client_abonnements');
        }

        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);
        $abonnement = $abonnementRepo->find($id);

        if (!$abonnement || !$abonnement->isActif()) {
            $this->addFlash('error', 'Abonnement non trouvé ou inactif.');
            return $this->redirectToRoute('client_abonnements');
        }

        // Vérifier si les promotions sont actives
        if ($abonnement->isEnPromotion() && !$abonnement->isPromotionActive()) {
            $this->addFlash('error', 'Cette promotion n\'est plus active.');
            return $this->redirectToRoute('client_abonnements');
        }

        if (!$client) {
            // Créer un client s'il n'existe pas
            $client = new Client();
            $client->setEmail($user->getEmail());
            $client->setNom($user->getLastName() ?? '');
            $client->setPrenom($user->getFirstName() ?? '');
            $client->setPointsFidelite(0);
            $entityManager->persist($client);
        }

        // Mettre à jour l'abonnement du client
        $client->setAbonnementActuel($abonnement);
        $client->setDateDebutAbonnement(new \DateTime());

        $dateFin = new \DateTime();
        $dateFin->add(new \DateInterval('P' . $abonnement->getDureeJours() . 'D'));
        $client->setDateFinAbonnement($dateFin);

        $entityManager->flush();

        $this->addFlash('success', 'Abonnement souscrit avec succès ! Votre abonnement est maintenant actif.');

        return $this->redirectToRoute('client_dashboard');
    }

    private function calculerSeancesRestantes(?Client $client): int
    {
        if (!$client || !$client->getAbonnementActuel()) {
            return 0;
        }

        $abonnement = $client->getAbonnementActuel();

        // Pour les abonnements à séances limitées
        if (in_array($abonnement->getType(), ['seance', 'carnet'])) {
            // Logique pour calculer les séances restantes
            // À implémenter selon la logique métier
            return 10; // Valeur par défaut
        }

        // Pour les abonnements illimités
        if ($client->getDateFinAbonnement() && $client->getDateFinAbonnement() > new \DateTime()) {
            return 999; // Illimité
        }

        return 0;
    }
}
