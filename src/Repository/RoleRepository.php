<?php

namespace KCPocket\Repository;

use KCPocket\Model\Role;
use KCPocket\Util\Database;

class RoleRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findById(string $id): ?Role
    {
        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $roleData = $stmt->fetch();
        return $roleData ? Role::fromArray($roleData) : null;
    }

    public function findByName(string $name): ?Role
    {
        $stmt = $this->pdo->prepare("SELECT * FROM roles WHERE name = :name");
        $stmt->execute([":name" => $name]);
        $roleData = $stmt->fetch();
        return $roleData ? Role::fromArray($roleData) : null;
    }

    public function save(Role $role): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO roles (id, name, description, created_at) " .
            "VALUES (:id, :name, :description, :created_at)"
        );
        return $stmt->execute([
            ":id" => $role->id,
            ":name" => $role->name,
            ":description" => $role->description,
            ":created_at" => $role->created_at
        ]);
    }

    public function update(Role $role): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE roles SET name = :name, description = :description WHERE id = :id"
        );
        return $stmt->execute([
            ":name" => $role->name,
            ":description" => $role->description,
            ":id" => $role->id
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM roles WHERE id = :id");
        return $stmt->execute([":id" => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM roles");
        $rolesData = $stmt->fetchAll();
        return array_map(fn($data) => Role::fromArray($data), $rolesData);
    }
}
