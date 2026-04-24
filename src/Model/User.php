<?php

namespace KCPocket\Model;

class User
{
    public string $id;
    public string $username;
    public string $email;
    public string $password_hash;
    public ?string $cpf;
    public int $enabled;
    public int $change_password_required;
    public int $token_agreement_lgpd;
    public ?int $last_login;
    public int $created_at;
    public ?int $updated_at;

    public function __construct(
        string $id,
        string $username,
        string $email,
        string $password_hash,
        ?string $cpf,
        int $enabled,
        int $change_password_required,
        int $token_agreement_lgpd,
        ?int $last_login,
        int $created_at,
        ?int $updated_at
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->cpf = $cpf;
        $this->enabled = $enabled;
        $this->change_password_required = $change_password_required;
        $this->token_agreement_lgpd = $token_agreement_lgpd;
        $this->last_login = $last_login;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["id"],
            $data["username"],
            $data["email"],
            $data["password_hash"],
            $data["cpf"] ?? null,
            $data["enabled"] ?? 1,
            $data["change_password_required"] ?? 0,
            $data["token_agreement_lgpd"] ?? 0,
            $data["last_login"] ?? null,
            $data["created_at"],
            $data["updated_at"] ?? null
        );
    }
}
