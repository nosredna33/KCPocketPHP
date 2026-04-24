<?php

namespace KCPocket\Service;

use KCPocket\Model\Permission;
use KCPocket\Repository\PermissionRepository;
use Ramsey\Uuid\Uuid;

class PermissionService
{
    private PermissionRepository $permissionRepository;

    public function __construct()
    {
        $this->permissionRepository = new PermissionRepository();
    }

    public function findById(string $id): ?Permission
    {
        return $this->permissionRepository->findById($id);
    }

    public function findByName(string $name): ?Permission
    {
        return $this->permissionRepository->findByName($name);
    }

    public function findAll(): array
    {
        return $this->permissionRepository->findAll();
    }

    public function createPermission(string $name, ?string $description = null): ?Permission
    {
        $permission = new Permission(Uuid::uuid4()->toString(), $name, $description, time());
        if ($this->permissionRepository->save($permission)) {
            return $permission;
        }
        return null;
    }

    public function updatePermission(string $id, string $name, ?string $description = null): ?Permission
    {
        $permission = $this->permissionRepository->findById($id);
        if ($permission) {
            $permission->name = $name;
            $permission->description = $description;
            if ($this->permissionRepository->update($permission)) {
                return $permission;
            }
        }
        return null;
    }

    public function deletePermission(string $id): bool
    {
        return $this->permissionRepository->delete($id);
    }
}
