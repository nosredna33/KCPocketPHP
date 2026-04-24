<?php

namespace KCPocket\Model;

class Permission
{
    public string $id;
    public string $name;
    public ?string $description;
    public int $created_at;

    public function __construct(
        string $id,
        string $name,
        ?string $description,
        int $created_at
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->created_at = $created_at;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["id"],
            $data["name"],
            $data["description"] ?? null,
            $data["created_at"]
        );
    }
}
