<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/contact', name: 'admin_contact_')]
class ContactController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ContactRepository $contactRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $contacts = $contactRepository->findLatest(50);

        return $this->render('admin/contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
