<?php

namespace KCPocket\Model;

class RolePermission
{
    public string $role_id;
    public string $permission_id;

    public function __construct(string $role_id, string $permission_id)
    {
        $this->role_id = $role_id;
        $this->permission_id = $permission_id;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["role_id"],
            $data["permission_id"]
        );
    }
}
