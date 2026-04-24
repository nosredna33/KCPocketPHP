<?php

namespace KCPocket\Service;

use KCPocket\Model\User;
use KCPocket\Model\OAuthClient;
use KCPocket\Repository\OAuthClientRepository;
use KCPocket\Repository\RefreshTokenRepository;
use KCPocket\Model\RefreshToken;
use Ramsey\Uuid\Uuid;

class OAuth2Service
{
    private OAuthClientRepository $clientRepository;
    private RefreshTokenRepository $refreshTokenRepository;
    private JwtService $jwtService;
    private UserService $userService;

    public function __construct()
    {
        $this->clientRepository = new OAuthClientRepository();
        $this->refreshTokenRepository = new RefreshTokenRepository();
        $this->jwtService = new JwtService();
        $this->userService = new UserService();
    }

    public function validateClient(string $clientId, string $clientSecret): ?OAuthClient
    {
        $client = $this->clientRepository->findByClientId($clientId);
        if ($client && password_verify($clientSecret, $client->client_secret)) {
            return $client;
        }
        return null;
    }

    public function generateAccessToken(User $user): string
    {
        // Obter permissões agregadas para incluir no token
        $permissions = $this->userService->getUserPermissions($user->id);
        $permNames = array_map(fn($p) => $p->name, $permissions);

        $payload = [
            'iss' => 'kcpocket-php',
            'sub' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'permissions' => $permNames,
            'iat' => time(),
            'exp' => time() + 3600
        ];
        return $this->jwtService->encode($payload);
    }

    public function generateRefreshToken(User $user, string $clientId): string
    {
        $token = bin2hex(random_bytes(32));
        $refreshToken = new RefreshToken(
            Uuid::uuid4()->toString(),
            $user->id,
            $clientId,
            hash('sha256', $token),
            time() + 604800, // 7 days
            0,
            time()
        );
        $this->refreshTokenRepository->save($refreshToken);
        return $token;
    }

    public function refreshAccessToken(string $token, string $clientId): ?array
    {
        $tokenHash = hash('sha256', $token);
        $refreshToken = $this->refreshTokenRepository->findByTokenHash($tokenHash);

        if ($refreshToken && $refreshToken->client_id === $clientId && $refreshToken->expires_at > time() && !$refreshToken->revoked) {
            $user = $this->userService->findById($refreshToken->user_id);
            if ($user) {
                // Revoke old token (Token Rotation)
                $this->refreshTokenRepository->revoke($refreshToken->id);
                
                // Generate new tokens
                return [
                    'access_token' => $this->generateAccessToken($user),
                    'refresh_token' => $this->generateRefreshToken($user, $clientId),
                    'expires_in' => 3600
                ];
            }
        }
        return null;
    }
}
