<?php

require_once __DIR__ . "/vendor/autoload.php";
use KCPocket\Util\Database;
use Ramsey\Uuid\Uuid;

try {
    $dataDir = __DIR__ . '/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0777, true);
    }

    $pdo = Database::getConnection();

    // Limpar tabelas existentes
    $pdo->exec("DROP TABLE IF EXISTS users");
    $pdo->exec("DROP TABLE IF EXISTS roles");
    $pdo->exec("DROP TABLE IF EXISTS permissions");
    $pdo->exec("DROP TABLE IF EXISTS user_roles");
    $pdo->exec("DROP TABLE IF EXISTS role_permissions");
    $pdo->exec("DROP TABLE IF EXISTS oauth_clients");
    $pdo->exec("DROP TABLE IF EXISTS refresh_tokens");

    // Criar tabelas
    $sql = "
    CREATE TABLE users (
        id TEXT PRIMARY KEY,
        username TEXT UNIQUE NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        cpf TEXT,
        enabled INTEGER DEFAULT 1,
        change_password_required INTEGER DEFAULT 0,
        token_agreement_lgpd INTEGER DEFAULT 0,
        last_login INTEGER,
        created_at INTEGER,
        updated_at INTEGER
    );

    CREATE TABLE roles (
        id TEXT PRIMARY KEY,
        name TEXT UNIQUE NOT NULL,
        description TEXT,
        created_at INTEGER
    );

    CREATE TABLE permissions (
        id TEXT PRIMARY KEY,
        name TEXT UNIQUE NOT NULL,
        description TEXT,
        created_at INTEGER
    );

    CREATE TABLE user_roles (
        user_id TEXT,
        role_id TEXT,
        PRIMARY KEY (user_id, role_id)
    );

    CREATE TABLE role_permissions (
        role_id TEXT,
        permission_id TEXT,
        PRIMARY KEY (role_id, permission_id)
    );

    CREATE TABLE oauth_clients (
        id TEXT PRIMARY KEY,
        client_secret TEXT NOT NULL,
        redirect_uri TEXT NOT NULL,
        scopes TEXT NOT NULL,
        grant_types TEXT NOT NULL,
        created_at INTEGER
    );
    ";

    $pdo->exec($sql);

    // Dados Iniciais com IDs Fixos
    $adminId = '00000000-0000-0000-0000-000000000001';
    $roleAdminId = '00000000-0000-0000-0000-000000000002';
    $permReadId = '00000000-0000-0000-0000-000000000003';
    $permWriteId = '00000000-0000-0000-0000-000000000004';
    $testClientId = 'test-client-id';

    $now = time();
    $passwordHash = password_hash('admin123', PASSWORD_BCRYPT);

    $pdo->prepare("INSERT INTO users (id, username, email, password_hash, created_at) VALUES (?, ?, ?, ?, ?)")
        ->execute([$adminId, 'admin', 'admin@example.com', $passwordHash, $now]);

    $pdo->prepare("INSERT INTO roles (id, name, description, created_at) VALUES (?, ?, ?, ?)")
        ->execute([$roleAdminId, 'ROLE_ADMIN', 'Administrador do Sistema', $now]);

    $pdo->prepare("INSERT INTO permissions (id, name, description, created_at) VALUES (?, ?, ?, ?)")
        ->execute([$permReadId, 'READ_PRIVILEGE', 'Permissão de Leitura', $now]);

    $pdo->prepare("INSERT INTO permissions (id, name, description, created_at) VALUES (?, ?, ?, ?)")
        ->execute([$permWriteId, 'WRITE_PRIVILEGE', 'Permissão de Escrita', $now]);

    $pdo->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)")
        ->execute([$adminId, $roleAdminId]);

    $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)")
        ->execute([$roleAdminId, $permReadId]);

    $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)")
        ->execute([$roleAdminId, $permWriteId]);

    $pdo->prepare("INSERT INTO oauth_clients (id, client_secret, redirect_uri, grant_types, scopes, created_at) VALUES (?, ?, ?, ?, ?, ?)")
        ->execute([$testClientId, 'test-client-secret', '/callback_example.php', 'password,client_credentials', 'openid,profile,email', $now]);

    echo "Banco de dados reinicializado com sucesso!\n";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}
