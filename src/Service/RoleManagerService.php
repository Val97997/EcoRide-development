<?php
// src/Service/RoleManager.php
namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RoleManagerService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addRole(User $user, string $role): void
    {
        $roles = $user->getRoles();

        if (!in_array($role, $roles, true)) {
            $roles[] = $role;
            $user->setRoles($roles);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    public function removeRole(User $user, string $role): void
    {
        $roles = $user->getRoles();
        $roles = array_diff($roles, [$role]);

        $user->setRoles($roles);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function hasRole(User $user, string $role): bool
    {
        return in_array($role, $user->getRoles(), true);
    }
}
