<?php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client', name: 'client_')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ClientRepository $clientRepository): Response
    {
        $clients = $clientRepository->findAll();

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
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

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Client $client, EntityManagerInterface $em): Response
    {
        $em->remove($client);
        $em->flush();

        return $this->redirectToRoute('client_index');
    }
}
