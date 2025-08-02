<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Coach;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\CoachRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard')]
    public function dashboard(
        UserRepository $userRepo,
        ClientRepository $clientRepo,
        CoachRepository $coachRepo
    ): Response {
        // Statistiques pour le dashboard
        $stats = [
            'total_users' => $userRepo->count([]),
            'total_clients' => $userRepo->countByRole('ROLE_CLIENT'),
            'total_coaches' => $userRepo->countByRole('ROLE_COACH'),
            'total_admins' => $userRepo->countByRole('ROLE_ADMIN'),
            'recent_members' => $userRepo->findRecentMembers(5),
            'active_users' => $userRepo->count(['isActive' => true]),
            'inactive_users' => $userRepo->count(['isActive' => false]),
        ];

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats
        ]);
    }

    #[Route('/users', name: 'admin_users_list')]
    public function usersList(Request $request, UserRepository $userRepo): Response
    {
        $search = $request->query->get('search', '');
        $role = $request->query->get('role', '');

        $qb = $userRepo->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC');

        if ($search) {
            $qb->andWhere('u.firstName LIKE :search OR u.lastName LIKE :search OR u.email LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($role) {
            $qb->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%"' . $role . '"%');
        }

        $users = $qb->getQuery()->getResult();

        return $this->render('admin/users/list.html.twig', [
            'users' => $users,
            'search' => $search,
            'role' => $role
        ]);
    }

    #[Route('/users/{id}', name: 'admin_user_show')]
    public function userShow(User $user): Response
    {
        return $this->render('admin/users/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/users/{id}/edit', name: 'admin_user_edit')]
    public function userEdit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            // Mise à jour des informations de base
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setPhone($data['phone']);
            $user->setAddress($data['address']);
            $user->setCity($data['city']);
            $user->setActive(isset($data['isActive']));

            // Gestion des rôles
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->setRoles($data['roles']);
            }

            $em->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succès');
            return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/users/create', name: 'admin_user_create')]
    public function userCreate(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();

            $user = new User();
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $user->setEmail($data['email']);
            $user->setPhone($data['phone']);
            $user->setBirthDate(new \DateTime($data['birthDate']));
            $user->setGender($data['gender']);
            $user->setAddress($data['address']);
            $user->setCity($data['city']);

            // Gestion des rôles
            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->setRoles($data['roles']);
            }

            // Hash du mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
            $user->setVerified(true); // Auto-vérification pour les comptes admin

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur créé avec succès');
            return $this->redirectToRoute('admin_users_list');
        }

        return $this->render('admin/users/create.html.twig');
    }

    #[Route('/users/{id}/toggle-status', name: 'admin_user_toggle_status', methods: ['POST'])]
    public function toggleUserStatus(User $user, EntityManagerInterface $em): Response
    {
        $user->setActive(!$user->isActive());
        $em->flush();

        $status = $user->isActive() ? 'activé' : 'désactivé';
        $this->addFlash('success', "Utilisateur {$status} avec succès");

        return $this->redirectToRoute('admin_users_list');
    }

    #[Route('/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em): Response
    {
        // Vérifier qu'on ne supprime pas le dernier admin
        if ($user->isAdmin()) {
            $adminCount = $em->getRepository(User::class)->countByRole('ROLE_ADMIN');
            if ($adminCount <= 1) {
                $this->addFlash('error', 'Impossible de supprimer le dernier administrateur');
                return $this->redirectToRoute('admin_users_list');
            }
        }

        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('admin_users_list');
    }

    #[Route('/settings', name: 'admin_settings')]
    public function settings(): Response
    {
        return $this->render('admin/settings.html.twig');
    }
}
