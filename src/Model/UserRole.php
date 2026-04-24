<?php

namespace KCPocket\Model;

class UserRole
{
    public string $user_id;
    public string $role_id;

    public function __construct(string $user_id, string $role_id)
    {
        $this->user_id = $user_id;
        $this->role_id = $role_id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["user_id"],
            $data["role_id"]
        );
    }
}
