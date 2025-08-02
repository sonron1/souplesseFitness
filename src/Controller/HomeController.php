<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\AbonnementRepository;
use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\ContactRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ClientRepository $clientRepository,
        ProduitRepository $produitRepository,
        CommandeRepository $commandeRepository,
        ContactRepository $contactRepository,
        AbonnementRepository $abonnementRepository
    ): Response {
        // Créer le formulaire de contact
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le message de contact
            $entityManager->persist($contact);
            $entityManager->flush();

            // Ajouter un message de succès
            $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');

            // Rediriger pour éviter la resoumission
            return $this->redirectToRoute('app_home', ['_fragment' => 'contact']);
        }

        // Récupérer les abonnements actifs pour les utilisateurs connectés
        $abonnements = [];
        if ($this->getUser()) {
            $abonnements = $abonnementRepository->findBy(
                ['actif' => true],
                ['prix' => 'ASC']
            );
        }

        // Statistiques pour le tableau de bord (si admin/coach)
        $stats = [];
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_COACH')) {
            $stats = [
                'total_clients' => $clientRepository->count([]),
                'total_produits' => $produitRepository->count([]),
                'total_commandes' => $commandeRepository->count([]),
                'commandes_en_attente' => $commandeRepository->count(['statut' => 'en_attente']),
            ];
        }

        return $this->render('home/index.html.twig', [
            'form' => $form,
            'stats' => $stats,
            'abonnements' => $abonnements,
        ]);
    }
}
