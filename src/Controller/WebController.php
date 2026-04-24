<?php

namespace KCPocket\Controller;

use Smarty;
use KCPocket\Service\UserService;
use KCPocket\Service\RoleService;
use KCPocket\Service\PermissionService;
use KCPocket\Service\OAuthClientService;
use KCPocket\Security\JwtKeyProvider;

class WebController
{
    private Smarty $smarty;
    private UserService $userService;
    private RoleService $roleService;
    private PermissionService $permissionService;
    private OAuthClientService $clientService;
    private JwtKeyProvider $jwtKeyProvider;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->userService = new UserService();
        $this->roleService = new RoleService();
        $this->permissionService = new PermissionService();
        $this->clientService = new OAuthClientService();
        $this->jwtKeyProvider = new JwtKeyProvider();
    }

    public function dashboard(): void
    {
        $users = $this->userService->findAll();
        $roles = $this->roleService->findAll();
        $permissions = $this->permissionService->findAll();
        $clients = $this->clientService->findAll();

        // Exemplo: Pegar permissões do primeiro usuário (admin) para demonstrar RBAC
        $adminPermissions = [];
        if (!empty($users)) {
            $adminPermissions = $this->userService->getUserPermissions($users[0]->id);
        }

        $jwtKeysExist = file_exists(__DIR__ . '/../../data/private_key.pem');

        $this->smarty->assign("title", "Dashboard");
        $this->smarty->assign("active_page", "dashboard");
        $this->smarty->assign("user_count", count($users));
        $this->smarty->assign("role_count", count($roles));
        $this->smarty->assign("permission_count", count($permissions));
        $this->smarty->assign("client_count", count($clients));
        $this->smarty->assign("admin_permissions", $adminPermissions);
        $this->smarty->assign("php_version", PHP_VERSION);
        $this->smarty->assign("smarty_version", Smarty::SMARTY_VERSION);
        $this->smarty->assign("jwt_keys_exist", $jwtKeysExist);
        
        $this->smarty->display("dashboard.tpl");
    }
}
