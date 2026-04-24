<?php

namespace KCPocket\Repository;

use KCPocket\Model\RolePermission;
use KCPocket\Util\Database;

class RolePermissionRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function assignPermissionToRole(string $roleId, string $permissionId): bool
    {
        $stmt = $this->pdo->prepare("INSERT OR IGNORE INTO role_permissions (role_id, permission_id) VALUES (:role_id, :permission_id)");
        return $stmt->execute([
            ":role_id" => $roleId,
            ":permission_id" => $permissionId
        ]);
    }

    public function removePermissionFromRole(string $roleId, string $permissionId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM role_permissions WHERE role_id = :role_id AND permission_id = :permission_id");
        return $stmt->execute([
            ":role_id" => $roleId,
            ":permission_id" => $permissionId
        ]);
    }

    public function findPermissionsByRoleId(string $roleId): array
    {
        $stmt = $this->pdo->prepare("SELECT p.id, p.name, p.description, p.created_at FROM permissions p JOIN role_permissions rp ON p.id = rp.permission_id WHERE rp.role_id = :role_id");
        $stmt->execute([":role_id" => $roleId]);
        $permissionsData = $stmt->fetchAll();
        return array_map(fn($data) => \KCPocket\Model\Permission::fromArray($data), $permissionsData);
    }

    public function findRolesByPermissionId(string $permissionId): array
    {
        $stmt = $this->pdo->prepare("SELECT r.id, r.name, r.description, r.created_at FROM roles r JOIN role_permissions rp ON r.id = rp.role_id WHERE rp.permission_id = :permission_id");
        $stmt->execute([":permission_id" => $permissionId]);
        $rolesData = $stmt->fetchAll();
        return array_map(fn($data) => \KCPocket\Model\Role::fromArray($data), $rolesData);
    }
}
