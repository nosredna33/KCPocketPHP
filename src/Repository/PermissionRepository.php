<?php

namespace KCPocket\Repository;

use KCPocket\Model\Permission;
use KCPocket\Util\Database;

class PermissionRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findById(string $id): ?Permission
    {
        $stmt = $this->pdo->prepare("SELECT * FROM permissions WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $permissionData = $stmt->fetch();
        return $permissionData ? Permission::fromArray($permissionData) : null;
    }

    public function findByName(string $name): ?Permission
    {
        $stmt = $this->pdo->prepare("SELECT * FROM permissions WHERE name = :name");
        $stmt->execute([":name" => $name]);
        $permissionData = $stmt->fetch();
        return $permissionData ? Permission::fromArray($permissionData) : null;
    }

    public function save(Permission $permission): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO permissions (id, name, description, created_at) " .
            "VALUES (:id, :name, :description, :created_at)"
        );
        return $stmt->execute([
            ":id" => $permission->id,
            ":name" => $permission->name,
            ":description" => $permission->description,
            ":created_at" => $permission->created_at
        ]);
    }

    public function update(Permission $permission): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE permissions SET name = :name, description = :description WHERE id = :id"
        );
        return $stmt->execute([
            ":name" => $permission->name,
            ":description" => $permission->description,
            ":id" => $permission->id
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM permissions WHERE id = :id");
        return $stmt->execute([":id" => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM permissions");
        $permissionsData = $stmt->fetchAll();
        return array_map(fn($data) => Permission::fromArray($data), $permissionsData);
    }

    public function findByRoleId(string $roleId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT p.* FROM permissions p JOIN role_permissions rp ON p.id = rp.permission_id WHERE rp.role_id = :role_id"
        );
        $stmt->execute([":role_id" => $roleId]);
        $permissionsData = $stmt->fetchAll();
        return array_map(fn($data) => Permission::fromArray($data), $permissionsData);
    }
}
