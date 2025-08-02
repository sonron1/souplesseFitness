<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();
        $roles = $user->getRoles();

        // Redirection selon le rôle (par ordre de priorité)
        if (in_array('ROLE_ADMIN', $roles)) {
            $redirectUrl = $this->router->generate('admin_dashboard');
        } elseif (in_array('ROLE_COACH', $roles)) {
            $redirectUrl = $this->router->generate('coach_dashboard');
        } elseif (in_array('ROLE_CLIENT', $roles)) {
            $redirectUrl = $this->router->generate('client_dashboard');
        } else {
            // Par défaut, rediriger vers l'accueil
            $redirectUrl = $this->router->generate('app_home');
        }

        return new RedirectResponse($redirectUrl);
    }
}
