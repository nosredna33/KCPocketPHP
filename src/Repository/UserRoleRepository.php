<?php

namespace KCPocket\Repository;

use KCPocket\Model\UserRole;
use KCPocket\Util\Database;

class UserRoleRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function assignRoleToUser(string $userId, string $roleId): bool
    {
        $stmt = $this->pdo->prepare("INSERT OR IGNORE INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)");
        return $stmt->execute([
            ":user_id" => $userId,
            ":role_id" => $roleId
        ]);
    }

    public function removeRoleFromUser(string $userId, string $roleId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM user_roles WHERE user_id = :user_id AND role_id = :role_id");
        return $stmt->execute([
            ":user_id" => $userId,
            ":role_id" => $roleId
        ]);
    }

    public function findRolesByUserId(string $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT r.id, r.name, r.description, r.created_at FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = :user_id");
        $stmt->execute([":user_id" => $userId]);
        $rolesData = $stmt->fetchAll();
        return array_map(fn($data) => \KCPocket\Model\Role::fromArray($data), $rolesData);
    }

    public function findUsersByRoleId(string $roleId): array
    {
        $stmt = $this->pdo->prepare("SELECT u.id, u.username, u.email, u.password_hash, u.cpf, u.enabled, u.change_password_required, u.token_agreement_lgpd, u.last_login, u.created_at, u.updated_at FROM users u JOIN user_roles ur ON u.id = ur.user_id WHERE ur.role_id = :role_id");
        $stmt->execute([":role_id" => $roleId]);
        $usersData = $stmt->fetchAll();
        return array_map(fn($data) => \KCPocket\Model\User::fromArray($data), $usersData);
    }
}
