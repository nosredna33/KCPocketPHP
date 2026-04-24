<?php

namespace KCPocket\Model;

class AuthorizationCode
{
    public string $id;
    public string $user_id;
    public string $client_id;
    public string $code;
    public ?string $code_challenge;
    public ?string $code_challenge_method;
    public string $redirect_uri;
    public string $scopes;
    public int $expires_at;
    public int $used;
    public int $created_at;

    public function __construct(
        string $id,
        string $user_id,
        string $client_id,
        string $code,
        ?string $code_challenge,
        ?string $code_challenge_method,
        string $redirect_uri,
        string $scopes,
        int $expires_at,
        int $used,
        int $created_at
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->client_id = $client_id;
        $this->code = $code;
        $this->code_challenge = $code_challenge;
        $this->code_challenge_method = $code_challenge_method;
        $this->redirect_uri = $redirect_uri;
        $this->scopes = $scopes;
        $this->expires_at = $expires_at;
        $this->used = $used;
        $this->created_at = $created_at;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["id"],
            $data["user_id"],
            $data["client_id"],
            $data["code"],
            $data["code_challenge"] ?? null,
            $data["code_challenge_method"] ?? null,
            $data["redirect_uri"],
            $data["scopes"],
            $data["expires_at"],
            $data["used"] ?? 0,
            $data["created_at"]
        );
    }
}
