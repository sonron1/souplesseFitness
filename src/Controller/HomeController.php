<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        ClientRepository $clientRepository,
        ProduitRepository $produitRepository,
        CommandeRepository $commandeRepository
    ): Response {
        // Statistiques pour le tableau de bord
        $stats = [
            'total_clients' => $clientRepository->count([]),
            'total_produits' => $produitRepository->count([]),
            'total_commandes' => $commandeRepository->count([]),
            'commandes_en_attente' => $commandeRepository->count(['statut' => 'en_attente']),
        ];

        // Dernières commandes
        $dernieresCommandes = $commandeRepository->findBy(
            [],
            ['dateCommande' => 'DESC'],
            5
        );

        // Produits avec stock faible (moins de 10)
        $produitsStockFaible = $produitRepository->createQueryBuilder('p')
            ->where('p.stock < 10')
            ->orderBy('p.stock', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        return $this->render('home/index.html.twig', [
            'stats' => $stats,
            'dernieres_commandes' => $dernieresCommandes,
            'produits_stock_faible' => $produitsStockFaible,
        ]);
    }
}
