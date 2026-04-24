<?php

namespace KCPocket\Controller;

use Smarty;
use KCPocket\Service\UserService;

class AuthController
{
    private Smarty $smarty;
    private UserService $userService;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->userService = new UserService();
    }

    public function showLogin(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirectAfterLogin();
            return;
        }
        $this->smarty->display("login.tpl");
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userService->userRepository->findByUsername($username);
        if (!$user) {
            $user = $this->userService->userRepository->findByEmail($username);
        }

        if ($user && password_verify($password, $user->password_hash)) {
            if (!$user->enabled) {
                $this->smarty->assign("error", "Sua conta está desativada.");
                $this->showLogin();
                return;
            }

            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            
            $this->redirectAfterLogin();
            return;
        }

        $this->smarty->assign("error", "Usuário ou senha inválidos.");
        $this->showLogin();
    }

    private function redirectAfterLogin(): void
    {
        $returnUrl = $_SESSION['auth_return_url'] ?? '/dashboard';
        unset($_SESSION['auth_return_url']);
        header("Location: " . $returnUrl);
        exit();
    }

    public function logout(): void
    {
        session_destroy();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        header("Location: /login");
        exit();
    }

    public static function checkAuth(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = '/' . trim($uri, '/');

        $publicPaths = ['/login', '/oauth2/jwks', '/oauth2/token', '/oauth2/authorize', '/oauth2/curl'];

        if (!isset($_SESSION['user_id']) && !in_array($uri, $publicPaths)) {
            header("Location: /login");
            exit();
        }
    }
}
