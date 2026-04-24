<?php

namespace KCPocket\Repository;

use KCPocket\Model\AuthorizationCode;
use KCPocket\Util\Database;

class AuthorizationCodeRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findById(string $id): ?AuthorizationCode
    {
        $stmt = $this->pdo->prepare("SELECT * FROM authorization_codes WHERE id = :id");
        $stmt->execute([":id" => $id]);
        $codeData = $stmt->fetch();
        return $codeData ? AuthorizationCode::fromArray($codeData) : null;
    }

    public function findByCode(string $code): ?AuthorizationCode
    {
        $stmt = $this->pdo->prepare("SELECT * FROM authorization_codes WHERE code = :code AND used = 0 AND expires_at > :now");
        $stmt->execute([
            ":code" => $code,
            ":now" => time()
        ]);
        $codeData = $stmt->fetch();
        return $codeData ? AuthorizationCode::fromArray($codeData) : null;
    }

    public function save(AuthorizationCode $authCode): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO authorization_codes (id, user_id, client_id, code, code_challenge, code_challenge_method, redirect_uri, scopes, expires_at, used, created_at) " .
            "VALUES (:id, :user_id, :client_id, :code, :code_challenge, :code_challenge_method, :redirect_uri, :scopes, :expires_at, :used, :created_at)"
        );
        return $stmt->execute([
            ":id" => $authCode->id,
            ":user_id" => $authCode->user_id,
            ":client_id" => $authCode->client_id,
            ":code" => $authCode->code,
            ":code_challenge" => $authCode->code_challenge,
            ":code_challenge_method" => $authCode->code_challenge_method,
            ":redirect_uri" => $authCode->redirect_uri,
            ":scopes" => $authCode->scopes,
            ":expires_at" => $authCode->expires_at,
            ":used" => $authCode->used,
            ":created_at" => $authCode->created_at
        ]);
    }

    public function markAsUsed(string $codeId): bool
    {
        $stmt = $this->pdo->prepare("UPDATE authorization_codes SET used = 1 WHERE id = :id");
        return $stmt->execute([":id" => $codeId]);
    }

    public function deleteExpiredCodes(): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM authorization_codes WHERE expires_at <= :now");
        return $stmt->execute([":now" => time()]);
    }
}
