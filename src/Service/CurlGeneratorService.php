<?php

namespace KCPocket\Service;

class CurlGeneratorService
{
    private string $baseUrl;

    public function __construct(string $baseUrl = 'http://localhost:8000')
    {
        $this->baseUrl = $baseUrl;
    }

    public function generateTokenExchangeCurl(string $clientId, string $clientSecret, string $code, string $redirectUri, string $codeVerifier): array
    {
        $command = <<<CURL
curl -X POST \n  {$this->baseUrl}/oauth2/token \n  -H "Content-Type: application/x-www-form-urlencoded" \n  -d "grant_type=authorization_code&client_id={$clientId}&client_secret={$clientSecret}&code={$code}&redirect_uri={$redirectUri}&code_verifier={$codeVerifier}"
CURL;

        return [
            'description' => 'Troca de Código de Autorização por Token de Acesso',
            'command' => $command,
            'response' => json_encode([
                'access_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...', // Exemplo
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'refresh_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'
            ], JSON_PRETTY_PRINT)
        ];
    }

    public function generateRefreshTokenCurl(string $clientId, string $refreshToken): array
    {
        $command = <<<CURL
curl -X POST \n  {$this->baseUrl}/oauth2/token \n  -H "Content-Type: application/x-www-form-urlencoded" \n  -d "grant_type=refresh_token&client_id={$clientId}&refresh_token={$refreshToken}"
CURL;

        return [
            'description' => 'Renovação de Token de Acesso usando Refresh Token',
            'command' => $command,
            'response' => json_encode([
                'access_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...', // Novo token
                'token_type' => 'Bearer',
                'expires_in' => 3600,
                'refresh_token' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...'
            ], JSON_PRETTY_PRINT)
        ];
    }

    public function generateJwksCurl(): array
    {
        $command = <<<CURL
curl -X GET \n  {$this->baseUrl}/oauth2/jwks
CURL;

        return [
            'description' => 'Obter JSON Web Key Set (JWKS)',
            'command' => $command,
            'response' => json_encode([
                'keys' => [
                    [
                        'kty' => 'RSA',
                        'use' => 'sig',
                        'alg' => 'RS256',
                        'kid' => 'kcpocket-php-key',
                        'n' => 'dummy_n_value',
                        'e' => 'dummy_e_value'
                    ]
                ]
            ], JSON_PRETTY_PRINT)
        ];
    }

    // Exemplo para um endpoint administrativo (requer token de acesso)
    public function generateAdminUsersListCurl(string $accessToken): array
    {
        $command = <<<CURL
curl -X GET \n  {$this->baseUrl}/admin/users \n  -H "Authorization: Bearer {$accessToken}"
CURL;

        return [
            'description' => 'Listar Usuários Administrativos',
            'command' => $command,
            'response' => json_encode([
                ['id' => 'user1', 'username' => 'testuser', 'email' => 'test@example.com'],
                ['id' => 'user2', 'username' => 'anotheruser', 'email' => 'another@example.com']
            ], JSON_PRETTY_PRINT)
        ];
    }
}
