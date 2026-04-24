<?php

namespace KCPocket\Service;

use KCPocket\Model\Role;
use KCPocket\Repository\RoleRepository;
use KCPocket\Repository\RolePermissionRepository;
use Ramsey\Uuid\Uuid;

class RoleService
{
    private RoleRepository $roleRepository;
    private RolePermissionRepository $rolePermissionRepository;

    public function __construct()
    {
        $this->roleRepository = new RoleRepository();
        $this->rolePermissionRepository = new RolePermissionRepository();
    }

    public function findById(string $id): ?Role
    {
        return $this->roleRepository->findById($id);
    }

    public function findByName(string $name): ?Role
    {
        return $this->roleRepository->findByName($name);
    }

    public function findAll(): array
    {
        return $this->roleRepository->findAll();
    }

    public function createRole(string $name, ?string $description = null): ?Role
    {
        $role = new Role(Uuid::uuid4()->toString(), $name, $description, time());
        if ($this->roleRepository->save($role)) {
            return $role;
        }
        return null;
    }

    public function updateRole(string $id, string $name, ?string $description = null): ?Role
    {
        $role = $this->roleRepository->findById($id);
        if ($role) {
            $role->name = $name;
            $role->description = $description;
            if ($this->roleRepository->update($role)) {
                return $role;
            }
        }
        return null;
    }

    public function deleteRole(string $id): bool
    {
        return $this->roleRepository->delete($id);
    }

    public function assignPermissionToRole(string $roleId, string $permissionId): bool
    {
        return $this->rolePermissionRepository->assignPermissionToRole($roleId, $permissionId);
    }

    public function removePermissionFromRole(string $roleId, string $permissionId): bool
    {
        return $this->rolePermissionRepository->removePermissionFromRole($roleId, $permissionId);
    }

    public function getPermissionsForRole(string $roleId): array
    {
        return $this->rolePermissionRepository->findPermissionsByRoleId($roleId);
    }
}
