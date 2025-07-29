<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Compter les utilisateurs par rôle
     */
    public function countByRole(string $role): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role . '"%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Trouver les utilisateurs par rôle
     */
    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"' . $role . '"%')
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les utilisateurs récents
     */
    public function findRecentUsers(int $limit = 5): array
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouver les clients récents
     */
    public function findRecentMembers(int $limit = 5): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_CLIENT"%')
            ->orderBy('u.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Rechercher les utilisateurs par nom/email
     */
    public function search(string $term): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.firstName LIKE :term OR u.lastName LIKE :term OR u.email LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            ->orderBy('u.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
