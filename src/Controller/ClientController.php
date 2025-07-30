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

        // Cours déjà réservés par le client
        $cours_reserves = $client ? $client->getCoursInscrits() : [];

        return $this->render('client/reservations.html.twig', [
            'user' => $user,
            'client' => $client,
            'cours_disponibles' => $cours_disponibles,
            'cours_reserves' => $cours_reserves,
            'abonnement_actif' => $abonnementActif,
        ]);
    }

    #[Route('/reserver-cours/{id}', name: 'client_reserver_cours', methods: ['POST'])]
    public function reserverCours(
        int $id,
        CoursRepository $coursRepo,
        ClientRepository $clientRepo,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);
        $cours = $coursRepo->find($id);

        if (!$cours) {
            $this->addFlash('error', 'Cours introuvable.');
            return $this->redirectToRoute('client_reservations');
        }

        // Vérifier abonnement actif
        $abonnementActif = $client && $client->getAbonnementActuel() &&
            $client->getDateFinAbonnement() &&
            $client->getDateFinAbonnement() > new \DateTime();

        if (!$abonnementActif) {
            $this->addFlash('error', 'Vous devez avoir un abonnement actif pour réserver.');
            return $this->redirectToRoute('client_abonnements');
        }

        // Vérifier si déjà inscrit
        if ($client->getCoursInscrits()->contains($cours)) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à ce cours.');
            return $this->redirectToRoute('client_reservations');
        }

        // Vérifier la capacité
        if ($cours->getClients()->count() >= $cours->getCapaciteMax()) {
            $this->addFlash('error', 'Ce cours est complet.');
            return $this->redirectToRoute('client_reservations');
        }

        // Effectuer la réservation
        $client->addCoursInscrit($cours);
        $em->flush();

        $this->addFlash('success', "Vous êtes inscrit au cours '{$cours->getNom()}' du " . $cours->getDateHeure()->format('d/m/Y à H:i'));
        return $this->redirectToRoute('client_reservations');
    }

    #[Route('/annuler-cours/{id}', name: 'client_annuler_cours', methods: ['POST'])]
    public function annulerCours(
        int $id,
        CoursRepository $coursRepo,
        ClientRepository $clientRepo,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);
        $cours = $coursRepo->find($id);

        if (!$cours || !$client) {
            $this->addFlash('error', 'Cours ou client introuvable.');
            return $this->redirectToRoute('client_reservations');
        }

        if ($client->getCoursInscrits()->contains($cours)) {
            $client->removeCoursInscrit($cours);
            $em->flush();
            $this->addFlash('success', "Votre réservation pour le cours '{$cours->getNom()}' a été annulée.");
        } else {
            $this->addFlash('warning', 'Vous n\'êtes pas inscrit à ce cours.');
        }

        return $this->redirectToRoute('client_reservations');
    }

    #[Route('/abonnements', name: 'client_abonnements')]
    public function abonnements(
        AbonnementRepository $abonnementRepo,
        ClientRepository $clientRepo
    ): Response {
        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);
        $abonnements = $abonnementRepo->findBy(['actif' => true]);

        return $this->render('client/abonnements.html.twig', [
            'user' => $user,
            'client' => $client,
            'abonnements' => $abonnements,
        ]);
    }

    #[Route('/souscrire-abonnement/{id}', name: 'client_souscrire_abonnement', methods: ['POST'])]
    public function souscrireAbonnement(
        int $id,
        AbonnementRepository $abonnementRepo,
        ClientRepository $clientRepo,
        EntityManagerInterface $em
    ): Response {
        $user = $this->getUser();
        $client = $clientRepo->findOneBy(['email' => $user->getEmail()]);
        $abonnement = $abonnementRepo->find($id);

        if (!$abonnement) {
            $this->addFlash('error', 'Abonnement introuvable.');
            return $this->redirectToRoute('client_abonnements');
        }

        // Créer un client si il n'existe pas
        if (!$client) {
            $client = new Client();
            $client->setEmail($user->getEmail());
            $client->setNom($user->getLastName() ?? 'Client');
            $client->setPrenom($user->getFirstName() ?? 'Nouveau');
            $client->setDateInscription(new \DateTime());
            $client->setActif(true);
            $client->setNumeroMembre('MB' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT));
            $client->setPointsFidelite(0);
            $em->persist($client);
        }

        // Souscrire à l'abonnement
        $client->setAbonnementActuel($abonnement);
        $client->setTypeAbonnement($abonnement->getType());
        $client->setDateDebutAbonnement(new \DateTime());

        $dateFinAbonnement = new \DateTime();
        $dateFinAbonnement->add(new \DateInterval('P' . $abonnement->getDureeJours() . 'D'));
        $client->setDateFinAbonnement($dateFinAbonnement);

        $em->flush();

        $this->addFlash('success', "Félicitations ! Vous avez souscrit à l'abonnement '{$abonnement->getNom()}'. Votre abonnement est actif jusqu'au " . $dateFinAbonnement->format('d/m/Y'));

        return $this->redirectToRoute('client_dashboard');
    }

    private function calculerSeancesRestantes(?Client $client): int
    {
        if (!$client || !$client->getAbonnementActuel()) {
            return 0;
        }

        // Logique simplifiée - à adapter selon vos besoins
        $abonnement = $client->getAbonnementActuel();
        $seancesUtilisees = $client->getCoursInscrits()->count();

        // Par exemple, 10 séances par abonnement mensuel
        $seancesIncluses = match($abonnement->getType()) {
            'mensuel' => 10,
            'trimestriel' => 30,
            'annuel' => 120,
            default => 10
        };

        return max(0, $seancesIncluses - $seancesUtilisees);
    }

    // Anciens contrôleurs pour la gestion des entités Client
    #[Route('/liste', name: 'client_index')]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/new', name: 'client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $client = new Client();
            $client->setNom($request->request->get('nom'));
            $client->setPrenom($request->request->get('prenom'));
            $client->setEmail($request->request->get('email'));
            $client->setTelephone($request->request->get('telephone'));

            $em->persist($client);
            $em->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig');
    }

    #[Route('/{id}', name: 'client_show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $client->setNom($request->request->get('nom'));
            $client->setPrenom($request->request->get('prenom'));
            $client->setEmail($request->request->get('email'));
            $client->setTelephone($request->request->get('telephone'));

            $em->flush();

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/delete', name: 'client_delete', methods: ['POST'])]
    public function delete(Client $client, EntityManagerInterface $em): Response
    {
        $em->remove($client);
        $em->flush();

        return $this->redirectToRoute('client_index');
    }
}
