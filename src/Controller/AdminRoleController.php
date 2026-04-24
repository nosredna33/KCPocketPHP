<?php

namespace KCPocket\Controller;

use Smarty;
use KCPocket\Service\RoleService;
use KCPocket\Service\PermissionService;

class AdminRoleController
{
    private Smarty $smarty;
    private RoleService $roleService;
    private PermissionService $permissionService;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->roleService = new RoleService();
        $this->permissionService = new PermissionService();
    }

    public function index(): void
    {
        $roles = $this->roleService->findAll();
        $this->smarty->assign("title", "Papéis");
        $this->smarty->assign("active_page", "roles");
        $this->smarty->assign("roles", $roles);
        $this->smarty->display("roles.tpl");
    }

    public function create(): void
    {
        $this->smarty->assign("title", "Novo Papel");
        $this->smarty->assign("active_page", "roles");
        $this->smarty->display("role_form.tpl");
    }

    public function store(): void
    {
        $name = $_POST["name"] ?? null;
        $description = $_POST["description"] ?? null;

        if ($name) {
            $this->roleService->createRole($name, $description);
        }
        header("Location: /roles");
        exit();
    }

    public function edit(string $id): void
    {
        $role = $this->roleService->findById($id);
        if (!$role) {
            header("Location: /roles");
            exit();
        }
        $this->smarty->assign("title", "Editar Papel");
        $this->smarty->assign("active_page", "roles");
        $this->smarty->assign("role", $role);
        $this->smarty->display("role_form.tpl");
    }

    public function update(string $id): void
    {
        $name = $_POST["name"] ?? "";
        $description = $_POST["description"] ?? "";
        $this->roleService->updateRole($id, $name, $description);
        header("Location: /roles");
        exit();
    }

    public function delete(string $id): void
    {
        $this->roleService->deleteRole($id);
        header("Location: /roles");
        exit();
    }

    public function managePermissions(string $id): void
    {
        $role = $this->roleService->findById($id);
        if (!$role) {
            header("Location: /roles");
            exit();
        }

        $allPermissions = $this->permissionService->findAll();
        $rolePermissions = $this->roleService->getPermissionsForRole($id);
        $rolePermissionIds = array_map(fn($p) => $p->id, $rolePermissions);

        $this->smarty->assign("title", "Gerenciar Permissões");
        $this->smarty->assign("active_page", "roles");
        $this->smarty->assign("role", $role);
        $this->smarty->assign("all_permissions", $allPermissions);
        $this->smarty->assign("role_permission_ids", $rolePermissionIds);
        $this->smarty->display("role_permissions.tpl");
    }

    public function updatePermissions(string $id): void
    {
        $selectedPermissionIds = $_POST["permissions"] ?? [];
        $currentPermissions = $this->roleService->getPermissionsForRole($id);
        $currentPermissionIds = array_map(fn($p) => $p->id, $currentPermissions);

        foreach ($selectedPermissionIds as $permId) {
            if (!in_array($permId, $currentPermissionIds)) {
                $this->roleService->assignPermissionToRole($id, $permId);
            }
        }

        foreach ($currentPermissionIds as $permId) {
            if (!in_array($permId, $selectedPermissionIds)) {
                $this->roleService->removePermissionFromRole($id, $permId);
            }
        }

        header("Location: /roles");
        exit();
    }
}
