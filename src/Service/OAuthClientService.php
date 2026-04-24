<?php

namespace KCPocket\Service;

use KCPocket\Model\OAuthClient;
use KCPocket\Repository\OAuthClientRepository;
use Ramsey\Uuid\Uuid;

class OAuthClientService
{
    public OAuthClientRepository $clientRepository;

    public function __construct()
    {
        $this->clientRepository = new OAuthClientRepository();
    }

    public function findById(string $id): ?OAuthClient
    {
        return $this->clientRepository->findById($id);
    }

    public function findByClientId(string $clientId): ?OAuthClient
    {
        return $this->clientRepository->findByClientId($clientId);
    }

    public function findAll(): array
    {
        return $this->clientRepository->findAll();
    }

    public function createClient(
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $scopes,
        string $grantTypes,
        ?int $accessTokenTtl = 3600,
        ?int $refreshTokenTtl = 604800
    ): ?OAuthClient {
        $client = new OAuthClient(
            Uuid::uuid4()->toString(),
            $clientId,
            password_hash($clientSecret, PASSWORD_BCRYPT),
            $redirectUri,
            $scopes,
            $grantTypes,
            $accessTokenTtl,
            $refreshTokenTtl,
            time(),
            null
        );
        if ($this->clientRepository->save($client)) {
            return $client;
        }
        return null;
    }

    public function updateClient(
        string $id,
        string $clientId,
        string $clientSecret,
        string $redirectUri,
        string $scopes,
        string $grantTypes,
        ?int $accessTokenTtl = 3600,
        ?int $refreshTokenTtl = 604800
    ): ?OAuthClient {
        $client = $this->clientRepository->findById($id);
        if ($client) {
            $client->client_id = $clientId;
            if ($clientSecret) {
                $client->client_secret = password_hash($clientSecret, PASSWORD_BCRYPT);
            }
            $client->redirect_uri = $redirectUri;
            $client->scopes = $scopes;
            $client->grant_types = $grantTypes;
            $client->access_token_ttl = $accessTokenTtl;
            $client->refresh_token_ttl = $refreshTokenTtl;
            $client->updated_at = time();
            if ($this->clientRepository->update($client)) {
                return $client;
            }
        }
        return null;
    }

    public function deleteClient(string $id): bool
    {
        return $this->clientRepository->delete($id);
    }
}
