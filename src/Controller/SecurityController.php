<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, le rediriger selon son rôle
        if ($this->getUser()) {
            return $this->redirectToUserDashboard();
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Redirige l'utilisateur vers son tableau de bord approprié selon son rôle
     */
    private function redirectToUserDashboard(): Response
    {
        $user = $this->getUser();

        // Vérifier les rôles et rediriger en conséquence
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_dashboard');
        }

        if ($this->isGranted('ROLE_COACH')) {
            return $this->redirectToRoute('coach_dashboard');
        }

        if ($this->isGranted('ROLE_CLIENT')) {
            return $this->redirectToRoute('client_dashboard');
        }

        // Par défaut, rediriger vers l'accueil
        return $this->redirectToRoute('app_home');
    }
}
