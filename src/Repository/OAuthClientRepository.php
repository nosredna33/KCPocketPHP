<?php

namespace KCPocket\Repository;

use KCPocket\Model\OAuthClient;
use KCPocket\Util\Database;

class OAuthClientRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function findById(string $id): ?OAuthClient
    {
        $stmt = $this->pdo->prepare("SELECT * FROM oauth_clients WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $clientData = $stmt->fetch();
        return $clientData ? OAuthClient::fromArray($clientData) : null;
    }

    public function save(OAuthClient $client): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO oauth_clients (id, client_secret, redirect_uri, scopes, grant_types, created_at) " .
            "VALUES (:id, :client_secret, :redirect_uri, :scopes, :grant_types, :created_at)"
        );
        return $stmt->execute([
            ':id' => $client->id,
            ':client_secret' => $client->client_secret,
            ':redirect_uri' => $client->redirect_uri,
            ':scopes' => $client->scopes,
            ':grant_types' => $client->grant_types,
            ':created_at' => $client->created_at
        ]);
    }

    public function update(OAuthClient $client): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE oauth_clients SET client_secret = :client_secret, redirect_uri = :redirect_uri, " .
            "scopes = :scopes, grant_types = :grant_types WHERE id = :id"
        );
        return $stmt->execute([
            ':client_secret' => $client->client_secret,
            ':redirect_uri' => $client->redirect_uri,
            ':scopes' => $client->scopes,
            ':grant_types' => $client->grant_types,
            ':id' => $client->id
        ]);
    }

    public function delete(string $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM oauth_clients WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM oauth_clients");
        $clientsData = $stmt->fetchAll();
        return array_map(fn($data) => OAuthClient::fromArray($data), $clientsData);
    }
}
