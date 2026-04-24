<?php

namespace KCPocket\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use KCPocket\Model\User;
use KCPocket\Security\JwtKeyProvider;

class JwtService
{
    private JwtKeyProvider $keyProvider;
    private int $accessTokenTtl = 3600; // 1 hour
    private int $refreshTokenTtl = 604800; // 7 days

    public function __construct()
    {
        $this->keyProvider = new JwtKeyProvider();
    }

    public function generateAccessToken(User $user): string
    {
        $privateKey = $this->keyProvider->getPrivateKey();
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->accessTokenTtl;

        $payload = [
            'iss' => 'kcpocket-php',
            'aud' => 'kcpocket-php',
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'roles' => ['ROLE_USER'] // Placeholder, implement actual role retrieval later
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    public function generateRefreshToken(User $user, string $clientId): string
    {
        $privateKey = $this->keyProvider->getPrivateKey();
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->refreshTokenTtl;

        $payload = [
            'iss' => 'kcpocket-php',
            'aud' => 'kcpocket-php',
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $user->id,
            'client_id' => $clientId,
            'type' => 'refresh'
        ];

        return JWT::encode($payload, $privateKey, 'RS256');
    }

    public function validateToken(string $token): ?object
    {
        try {
            $publicKey = $this->keyProvider->getPublicKey();
            return JWT::decode($token, new Key($publicKey, 'RS256'));
        } catch (\Exception $e) {
            error_log('JWT validation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function hashToken(string $token): string
    {
        return hash('sha256', $token);
    }

    public function getRefreshTokenExpirationTime(): int
    {
        return time() + $this->refreshTokenTtl;
    }
}
