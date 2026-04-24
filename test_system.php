<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/config/smarty_config.php";

use KCPocket\Util\Database;
use KCPocket\Service\UserService;
use KCPocket\Service\JwtService;
use KCPocket\Security\JwtKeyProvider;

echo "--- KCPocket PHP System Test ---\n";

// 1. Test Database Connection
try {
    $pdo = Database::getConnection();
    echo "[OK] Database connection successful.\n";
} catch (\Exception $e) {
    echo "[ERROR] Database connection failed: " . $e->getMessage() . "\n";
}

// 2. Test User Service (Create and Find)
try {
    $userService = new UserService();
    $username = "testuser_" . time();
    $email = "test_" . time() . "@example.com";
    $user = $userService->createUser($username, $email, "password123");
    if ($user) {
        echo "[OK] User creation successful: {$user->username}\n";
        $foundUser = $userService->findById($user->id);
        if ($foundUser && $foundUser->username === $username) {
            echo "[OK] User retrieval successful.\n";
        } else {
            echo "[ERROR] User retrieval failed.\n";
        }
    } else {
        echo "[ERROR] User creation failed.\n";
    }
} catch (\Exception $e) {
    echo "[ERROR] User service test failed: " . $e->getMessage() . "\n";
}

// 3. Test JWT Service
try {
    $jwtService = new JwtService();
    $token = $jwtService->generateAccessToken($user);
    echo "[OK] JWT generation successful.\n";
    $decoded = $jwtService->validateToken($token);
    if ($decoded && $decoded->sub === $user->id) {
        echo "[OK] JWT validation successful.\n";
    } else {
        echo "[ERROR] JWT validation failed.\n";
    }
} catch (\Exception $e) {
    echo "[ERROR] JWT service test failed: " . $e->getMessage() . "\n";
}

// 4. Test Smarty Configuration
try {
    $smarty = getSmarty();
    if ($smarty instanceof Smarty) {
        echo "[OK] Smarty configuration successful.\n";
    } else {
        echo "[ERROR] Smarty configuration failed.\n";
    }
} catch (\Exception $e) {
    echo "[ERROR] Smarty test failed: " . $e->getMessage() . "\n";
}

echo "--- Test Completed ---\n";
