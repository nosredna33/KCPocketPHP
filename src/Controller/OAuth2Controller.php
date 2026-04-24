<?php

namespace KCPocket\Controller;

use Smarty;
use KCPocket\Service\JwtService;
use KCPocket\Service\OAuth2Service;
use KCPocket\Service\UserService;
use KCPocket\Service\OAuthClientService;
use KCPocket\Service\CurlGeneratorService;
use KCPocket\Security\JwtKeyProvider;

class OAuth2Controller
{
    private Smarty $smarty;
    private JwtService $jwtService;
    private OAuth2Service $oauth2Service;
    private UserService $userService;
    private OAuthClientService $clientService;
    private CurlGeneratorService $curlGeneratorService;
    private JwtKeyProvider $jwtKeyProvider;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->jwtService = new JwtService();
        $this->oauth2Service = new OAuth2Service();
        $this->userService = new UserService();
        $this->clientService = new OAuthClientService();
        $this->curlGeneratorService = new CurlGeneratorService();
        $this->jwtKeyProvider = new JwtKeyProvider();
    }

    public function authorize(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $clientId = $_GET['client_id'] ?? '';
        $redirectUri = $_GET['redirect_uri'] ?? '';
        $state = $_GET['state'] ?? '';

        if (!$clientId) {
            die("Client ID is required");
        }

        $client = $this->clientService->findById($clientId);
        if (!$client) {
            die("Invalid Client ID");
        }

        // Se não estiver logado, salvar a URL atual e ir para login
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['auth_return_url'] = $_SERVER['REQUEST_URI'];
            header("Location: /login");
            exit();
        }

        // Se logado, gerar token e redirecionar
        $user = $this->userService->userRepository->findById($_SESSION['user_id']);
        if (!$user) {
            session_destroy();
            header("Location: /login");
            exit();
        }

        $token = $this->oauth2Service->generateAccessToken($user);

        // Determinar URL de destino
        $targetUrl = $redirectUri ?: $client->redirect_uri;
        
        // Garantir que a URL seja absoluta para o redirecionamento
        $host = $_SERVER['HTTP_HOST'];
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        
        if (strpos($targetUrl, 'http') !== 0) {
            $targetUrl = $protocol . '://' . $host . '/' . ltrim($targetUrl, '/');
        }

        $separator = (strpos($targetUrl, '?') === false) ? '?' : '&';
        $finalUrl = $targetUrl . $separator . "access_token=" . $token;
        if ($state) {
            $finalUrl .= "&state=" . $state;
        }

        error_log("OAuth2 Authorize: Success. Redirecting to: " . $finalUrl);
        header("Location: " . $finalUrl);
        exit();
    }

    public function jwks(): void
    {
        header("Content-Type: application/json");
        try {
            $publicKeyDetails = $this->jwtKeyProvider->getPublicKeyDetails();
            echo json_encode([
                "keys" => [
                    $publicKeyDetails
                ]
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(["error" => "server_error", "error_description" => $e->getMessage()]);
        }
    }

    public function curlInfo(): void
    {
        $commands = [
            "Token Exchange (Password Grant)" => [
                "curl -X POST http://localhost:8000/oauth2/token -d 'grant_type=password&username=admin&password=admin123&client_id=test-client-id&client_secret=test-client-secret'"
            ],
            "JWKS Endpoint" => [
                "curl http://localhost:8000/oauth2/jwks"
            ],
            "Authorize Redirect (SSO)" => [
                "http://localhost:8000/oauth2/authorize?client_id=test-client-id&redirect_uri=/callback_example.php"
            ]
        ];

        $this->smarty->assign("title", "cURL Info - OAuth2");
        $this->smarty->assign("active_page", "oauth2_curl");
        $this->smarty->assign("curl_commands", $commands);
        $this->smarty->display("curl_info.tpl");
    }

    public function token(): void
    {
        header("Content-Type: application/json");
        $grantType = $_POST["grant_type"] ?? null;

        switch ($grantType) {
            case "password":
                $this->handlePasswordGrant();
                break;
            case "client_credentials":
                $this->handleClientCredentialsGrant();
                break;
            default:
                http_response_code(400);
                echo json_encode(["error" => "unsupported_grant_type"]);
                break;
        }
    }

    public function introspect(): void
    {
        header("Content-Type: application/json");
        $token = $_POST['token'] ?? '';

        if (!$token) {
            echo json_encode(["active" => false]);
            return;
        }

        try {
            $decoded = $this->jwtService->decodeToken($token);
            if ($decoded) {
                echo json_encode([
                    "active" => true,
                    "username" => $decoded->username ?? null,
                    "sub" => $decoded->sub ?? null,
                    "permissions" => $decoded->permissions ?? [],
                    "exp" => $decoded->exp ?? null,
                    "iat" => $decoded->iat ?? null
                ]);
                return;
            }
        } catch (\Exception $e) {
            error_log("Introspection error: " . $e->getMessage());
        }

        echo json_encode(["active" => false]);
    }

    private function handlePasswordGrant(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $clientId = $_POST['client_id'] ?? '';
        $clientSecret = $_POST['client_secret'] ?? '';

        $client = $this->oauth2Service->validateClient($clientId, $clientSecret);
        if (!$client) {
            http_response_code(401);
            echo json_encode(["error" => "invalid_client"]);
            return;
        }

        $user = $this->userService->userRepository->findByUsername($username);
        if (!$user) $user = $this->userService->userRepository->findByEmail($username);

        if ($user && password_verify($password, $user->password_hash)) {
            $token = $this->oauth2Service->generateAccessToken($user);
            echo json_encode([
                "access_token" => $token,
                "token_type" => "Bearer",
                "expires_in" => 3600
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "invalid_grant"]);
        }
    }

    private function handleClientCredentialsGrant(): void
    {
        $clientId = $_POST['client_id'] ?? '';
        $clientSecret = $_POST['client_secret'] ?? '';

        $client = $this->oauth2Service->validateClient($clientId, $clientSecret);
        if (!$client) {
            http_response_code(401);
            echo json_encode(["error" => "invalid_client"]);
            return;
        }

        $token = $this->oauth2Service->generateAccessToken(null);
        echo json_encode([
            "access_token" => $token,
            "token_type" => "Bearer",
            "expires_in" => 3600
        ]);
    }
}
