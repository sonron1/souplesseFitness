<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:assign-role',
    description: 'Assigne un rôle à un utilisateur',
)]
class AssignRoleCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email de l\'utilisateur')
            ->addArgument('role', InputArgument::REQUIRED, 'Rôle à assigner (admin, client, coach)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');

        // Validation du rôle
        $validRoles = ['admin' => 'ROLE_ADMIN', 'client' => 'ROLE_CLIENT', 'coach' => 'ROLE_COACH'];

        if (!array_key_exists($role, $validRoles)) {
            $io->error('Rôle invalide. Utilisez: admin, client ou coach');
            return Command::FAILURE;
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error('Utilisateur non trouvé avec cet email.');
            return Command::FAILURE;
        }

        $roleSymfony = $validRoles[$role];
        $currentRoles = $user->getRoles();

        if (!in_array($roleSymfony, $currentRoles)) {
            $currentRoles[] = $roleSymfony;
            $user->setRoles($currentRoles);
            $this->entityManager->flush();

            $io->success(sprintf('Rôle %s assigné à l\'utilisateur %s', $role, $email));
        } else {
            $io->info(sprintf('L\'utilisateur %s a déjà le rôle %s', $email, $role));
        }

        return Command::SUCCESS;
    }
}
