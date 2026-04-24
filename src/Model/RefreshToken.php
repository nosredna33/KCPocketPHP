<?php

namespace KCPocket\Model;

class RefreshToken
{
    public string $id;
    public string $user_id;
    public string $client_id;
    public string $token_hash;
    public int $expires_at;
    public int $revoked;
    public int $created_at;

    public function __construct(
        string $id,
        string $user_id,
        string $client_id,
        string $token_hash,
        int $expires_at,
        int $revoked,
        int $created_at
    ) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->client_id = $client_id;
        $this->token_hash = $token_hash;
        $this->expires_at = $expires_at;
        $this->revoked = $revoked;
        $this->created_at = $created_at;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["id"],
            $data["user_id"],
            $data["client_id"],
            $data["token_hash"],
            $data["expires_at"],
            $data["revoked"] ?? 0,
            $data["created_at"]
        );
    }
}
