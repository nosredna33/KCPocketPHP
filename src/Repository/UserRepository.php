<?php

namespace KCPocket\Repository;

use KCPocket\Model\User;
use KCPocket\Util\Database;

class UserRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findById(string $id): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $userData = $stmt->fetch();
        return $userData ? User::fromArray($userData) : null;
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $userData = $stmt->fetch();
        return $userData ? User::fromArray($userData) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $userData = $stmt->fetch();
        return $userData ? User::fromArray($userData) : null;
    }

    public function save(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (id, username, email, password_hash, cpf, enabled, change_password_required, token_agreement_lgpd, last_login, created_at, updated_at) " .
            "VALUES (:id, :username, :email, :password_hash, :cpf, :enabled, :change_password_required, :token_agreement_lgpd, :last_login, :created_at, :updated_at)"
        );
        return $stmt->execute([
            ':id' => $user->id,
            ':username' => $user->username,
            ':email' => $user->email,
            ':password_hash' => $user->password_hash,
            ':cpf' => $user->cpf,
            ':enabled' => $user->enabled,
            ':change_password_required' => $user->change_password_required,
            ':token_agreement_lgpd' => $user->token_agreement_lgpd,
            ':last_login' => $user->last_login,
            ':created_at' => $user->created_at,
            ':updated_at' => $user->updated_at
        ]);
    }

    public function update(User $user): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET username = :username, email = :email, password_hash = :password_hash, cpf = :cpf, " .
            "enabled = :enabled, change_password_required = :change_password_required, token_agreement_lgpd = :token_agreement_lgpd, " .
            "last_login = :last_login, updated_at = :updated_at WHERE id = :id"
        );
        return $stmt->execute([
            ':username' => $user->username,
            ':email' => $user->email,
            ':password_hash' => $user->password_hash,
            ':cpf' => $user->cpf,
            ':enabled' => $user->enabled,
            ':change_password_required' => $user->change_password_required,
            ':token_agreement_lgpd' => $user->token_agreement_lgpd,
            ':last_login' => $user->last_login,
            ':updated_at' => $user->updated_at,
            ':id' => $user->id
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $usersData = $stmt->fetchAll();
        return array_map(fn($data) => User::fromArray($data), $usersData);
    }
}
