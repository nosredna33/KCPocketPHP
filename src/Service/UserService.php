<?php

namespace KCPocket\Service;

use KCPocket\Model\User;
use KCPocket\Repository\UserRepository;
use KCPocket\Repository\UserRoleRepository;
use KCPocket\Repository\RolePermissionRepository;
use Ramsey\Uuid\Uuid;

class UserService
{
    public UserRepository $userRepository;
    private UserRoleRepository $userRoleRepository;
    private RolePermissionRepository $rolePermissionRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->userRoleRepository = new UserRoleRepository();
        $this->rolePermissionRepository = new RolePermissionRepository();
    }

    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function findById(string $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->userRepository->findByUsername($username);
    }

    public function createUser(string $username, string $email, string $password, ?string $cpf = null): ?User
    {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User(
            Uuid::uuid4()->toString(),
            $username,
            $email,
            $passwordHash,
            $cpf,
            1, // enabled
            0, // change_password_required
            0, // token_agreement_lgpd
            null, // last_login
            time(), // created_at
            null // updated_at
        );
        if ($this->userRepository->save($user)) {
            return $user;
        }
        return null;
    }

    /**
     * Obtém todas as permissões de um usuário (agregadas de seus papéis)
     */
    public function getUserPermissions(string $userId): array
    {
        $roles = $this->userRoleRepository->findRolesByUserId($userId);
        $allPermissions = [];
        
        foreach ($roles as $role) {
            $permissions = $this->rolePermissionRepository->findPermissionsByRoleId($role->id);
            foreach ($permissions as $permission) {
                $allPermissions[$permission->name] = $permission;
            }
        }
        
        return array_values($allPermissions);
    }

    public function validatePassword(User $user, string $password): bool
    {
        return password_verify($password, $user->password_hash);
    }

    public function assignRoleToUser(string $userId, string $roleId): bool
    {
        return $this->userRoleRepository->assignRoleToUser($userId, $roleId);
    }
}
