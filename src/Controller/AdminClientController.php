<?php

namespace KCPocket\Controller;

use Smarty;
use KCPocket\Service\OAuthClientService;

class AdminClientController
{
    private Smarty $smarty;
    private OAuthClientService $clientService;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->clientService = new OAuthClientService();
    }

    public function index(): void
    {
        $clients = $this->clientService->findAll();
        $this->smarty->assign("title", "Clientes OAuth");
        $this->smarty->assign("active_page", "clients");
        $this->smarty->assign("clients", $clients);
        $this->smarty->display("clients.tpl");
    }

    public function create(): void
    {
        $this->smarty->assign("title", "Novo Cliente OAuth");
        $this->smarty->assign("active_page", "clients");
        $this->smarty->display("client_form.tpl");
    }

    public function store(): void
    {
        $clientId = $_POST["client_id"] ?? null;
        $clientSecret = $_POST["client_secret"] ?? null;
        $redirectUri = $_POST["redirect_uri"] ?? null;
        $scopes = $_POST["scopes"] ?? null;
        $grantTypes = $_POST["grant_types"] ?? null;
        $accessTokenTtl = $_POST["access_token_ttl"] ?? null;
        $refreshTokenTtl = $_POST["refresh_token_ttl"] ?? null;

        if ($clientId && $clientSecret && $redirectUri && $scopes && $grantTypes) {
            $client = $this->clientService->createClient(
                $clientId, $clientSecret, $redirectUri, $scopes, $grantTypes, $accessTokenTtl, $refreshTokenTtl
            );
            if ($client) {
                header("Location: /clients");
                exit();
            } else {
                $this->smarty->assign("error", "Erro ao criar cliente OAuth.");
                $this->create();
            }
        } else {
            $this->smarty->assign("error", "Preencha todos os campos obrigatórios.");
            $this->create();
        }
    }

    public function edit(string $id): void
    {
        $client = $this->clientService->findById($id);
        if (!$client) {
            header("Location: /clients");
            exit();
        }
        $this->smarty->assign("title", "Editar Cliente OAuth");
        $this->smarty->assign("active_page", "clients");
        $this->smarty->assign("client", $client);
        $this->smarty->display("client_form.tpl");
    }

    public function update(string $id): void
    {
        $client = $this->clientService->findById($id);
        if (!$client) {
            header("Location: /clients");
            exit();
        }

        $clientId = $_POST["client_id"] ?? $client->client_id;
        $clientSecret = $_POST["client_secret"] ?? null; // Only update if provided
        $redirectUri = $_POST["redirect_uri"] ?? $client->redirect_uri;
        $scopes = $_POST["scopes"] ?? $client->scopes;
        $grantTypes = $_POST["grant_types"] ?? $client->grant_types;
        $accessTokenTtl = $_POST["access_token_ttl"] ?? $client->access_token_ttl;
        $refreshTokenTtl = $_POST["refresh_token_ttl"] ?? $client->refresh_token_ttl;

        if ($clientSecret) {
            $client->client_secret = password_hash($clientSecret, PASSWORD_BCRYPT);
        }
        $client->client_id = $clientId;
        $client->redirect_uri = $redirectUri;
        $client->scopes = $scopes;
        $client->grant_types = $grantTypes;
        $client->access_token_ttl = $accessTokenTtl;
        $client->refresh_token_ttl = $refreshTokenTtl;
        $client->updated_at = time();

        if ($this->clientService->clientRepository->update($client)) {
            header("Location: /clients");
            exit();
        } else {
            $this->smarty->assign("error", "Erro ao atualizar cliente OAuth.");
            $this->edit($id);
        }
    }

    public function delete(string $id): void
    {
        if ($this->clientService->deleteClient($id)) {
            header("Location: /clients");
            exit();
        } else {
            $this->smarty->assign("error", "Erro ao excluir cliente OAuth.");
            $this->index();
        }
    }
}
