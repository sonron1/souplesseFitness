<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeItem;
use App\Repository\CommandeRepository;
use App\Repository\ClientRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande', name: 'commande_')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findAll();

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        ClientRepository $clientRepository,
        ProduitRepository $produitRepository
    ): Response {
        if ($request->isMethod('POST')) {
            $client = $clientRepository->find($request->request->get('client_id'));

            $commande = new Commande();
            $commande->setClient($client);
            $commande->setDateCommande(new \DateTime());
            $commande->setStatut('en_attente');

            $em->persist($commande);
            $em->flush();

            return $this->redirectToRoute('commande_show', ['id' => $commande->getId()]);
        }

        $clients = $clientRepository->findAll();

        return $this->render('commande/new.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/add-item', name: 'add_item', methods: ['POST'])]
    public function addItem(
        Commande $commande,
        Request $request,
        EntityManagerInterface $em,
        ProduitRepository $produitRepository
    ): Response {
        $produit = $produitRepository->find($request->request->get('produit_id'));
        $quantite = (int) $request->request->get('quantite');

        $commandeItem = new CommandeItem();
        $commandeItem->setCommande($commande);
        $commandeItem->setProduit($produit);
        $commandeItem->setQuantite($quantite);
        $commandeItem->setPrixUnitaire($produit->getPrix());

        $em->persist($commandeItem);
        $em->flush();

        return $this->redirectToRoute('commande_show', ['id' => $commande->getId()]);
    }

    #[Route('/{id}/edit-status', name: 'edit_status', methods: ['POST'])]
    public function editStatus(Commande $commande, Request $request, EntityManagerInterface $em): Response
    {
        $commande->setStatut($request->request->get('statut'));
        $em->flush();

        return $this->redirectToRoute('commande_show', ['id' => $commande->getId()]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Commande $commande, EntityManagerInterface $em): Response
    {
        $em->remove($commande);
        $em->flush();

        return $this->redirectToRoute('commande_index');
    }
}
