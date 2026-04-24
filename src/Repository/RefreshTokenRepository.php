<?php

namespace KCPocket\Repository;

use KCPocket\Model\RefreshToken;
use KCPocket\Util\Database;

class RefreshTokenRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findById(string $id): ?RefreshToken
    {
        $stmt = $this->pdo->prepare("SELECT * FROM refresh_tokens WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $tokenData = $stmt->fetch();
        return $tokenData ? RefreshToken::fromArray($tokenData) : null;
    }

    public function findByTokenHash(string $tokenHash): ?RefreshToken
    {
        $stmt = $this->pdo->prepare("SELECT * FROM refresh_tokens WHERE token_hash = :token_hash AND revoked = 0 AND expires_at > :now");
        $stmt->execute([
            ":token_hash" => $tokenHash,
            ":now" => time()
        ]);
        $tokenData = $stmt->fetch();
        return $tokenData ? RefreshToken::fromArray($tokenData) : null;
    }

    public function save(RefreshToken $token): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO refresh_tokens (id, user_id, client_id, token_hash, expires_at, revoked, created_at) " .
            "VALUES (:id, :user_id, :client_id, :token_hash, :expires_at, :revoked, :created_at)"
        );
        return $stmt->execute([
            ":id" => $token->id,
            ":user_id" => $token->user_id,
            ":client_id" => $token->client_id,
            ":token_hash" => $token->token_hash,
            ":expires_at" => $token->expires_at,
            ":revoked" => $token->revoked,
            ":created_at" => $token->created_at
        ]);
    }

    public function revokeToken(string $tokenHash): bool
    {
        $stmt = $this->pdo->prepare("UPDATE refresh_tokens SET revoked = 1 WHERE token_hash = :token_hash");
        return $stmt->execute([":token_hash" => $tokenHash]);
    }

    public function revokeUserTokens(string $userId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE refresh_tokens SET revoked = 1 WHERE user_id = :user_id");
        return $stmt->execute([":user_id" => $userId]);
    }

    public function revokeClientTokens(string $clientId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE refresh_tokens SET revoked = 1 WHERE client_id = :client_id");
        return $stmt->execute([":client_id" => $clientId]);
    }

    public function deleteExpiredTokens(): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM refresh_tokens WHERE expires_at <= :now");
        return $stmt->execute([":now" => time()]);
    }
}
