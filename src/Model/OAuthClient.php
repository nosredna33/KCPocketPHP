<?php

namespace KCPocket\Model;

class OAuthClient
{
    public string $id;
    public string $client_secret;
    public string $redirect_uri;
    public string $scopes;
    public string $grant_types;
    public int $created_at;

    public function __construct(
        string $id,
        string $client_secret,
        string $redirect_uri,
        string $scopes,
        string $grant_types,
        int $created_at
    ) {
        $this->id = $id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        $this->scopes = $scopes;
        $this->grant_types = $grant_types;
        $this->created_at = $created_at;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["id"],
            $data["client_secret"],
            $data["redirect_uri"],
            $data["scopes"],
            $data["grant_types"],
            (int)$data["created_at"]
        );
    }
}
