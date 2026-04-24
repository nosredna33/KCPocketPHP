<?php

namespace KCPocket\Controller;

use Smarty;
use KCPocket\Service\PermissionService;

class AdminPermissionController
{
    private Smarty $smarty;
    private PermissionService $permissionService;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->permissionService = new PermissionService();
    }

    public function index(): void
    {
        $permissions = $this->permissionService->findAll();
        $this->smarty->assign("title", "Permissões");
        $this->smarty->assign("active_page", "permissions");
        $this->smarty->assign("permissions", $permissions);
        $this->smarty->display("permissions.tpl");
    }

    public function create(): void
    {
        $this->smarty->assign("title", "Nova Permissão");
        $this->smarty->assign("active_page", "permissions");
        $this->smarty->display("permission_form.tpl");
    }

    public function store(): void
    {
        $name = $_POST["name"] ?? null;
        $description = $_POST["description"] ?? null;

        if ($name) {
            $permission = $this->permissionService->createPermission($name, $description);
            if ($permission) {
                header("Location: /permissions");
                exit();
            } else {
                $this->smarty->assign("error", "Erro ao criar permissão.");
                $this->create();
            }
        } else {
            $this->smarty->assign("error", "Preencha o nome da permissão.");
            $this->create();
        }
    }

    public function edit(string $id): void
    {
        $permission = $this->permissionService->findById($id);
        if (!$permission) {
            header("Location: /permissions");
            exit();
        }
        $this->smarty->assign("title", "Editar Permissão");
        $this->smarty->assign("active_page", "permissions");
        $this->smarty->assign("permission", $permission);
        $this->smarty->display("permission_form.tpl");
    }

    public function update(string $id): void
    {
        $permission = $this->permissionService->findById($id);
        if (!$permission) {
            header("Location: /permissions");
            exit();
        }

        $name = $_POST["name"] ?? $permission->name;
        $description = $_POST["description"] ?? $permission->description;

        if ($this->permissionService->updatePermission($id, $name, $description)) {
            header("Location: /permissions");
            exit();
        } else {
            $this->smarty->assign("error", "Erro ao atualizar permissão.");
            $this->edit($id);
        }
    }

    public function delete(string $id): void
    {
        if ($this->permissionService->deletePermission($id)) {
            header("Location: /permissions");
            exit();
        } else {
            $this->smarty->assign("error", "Erro ao excluir permissão.");
            $this->index();
        }
    }
}
