<?php

namespace KCPocket\Controller;

use Smarty;
use KCPocket\Service\UserService;
use KCPocket\Service\RoleService;
use KCPocket\Repository\UserRoleRepository;

class AdminUserController
{
    private Smarty $smarty;
    private UserService $userService;
    private RoleService $roleService;
    private UserRoleRepository $userRoleRepository;

    public function __construct(Smarty $smarty)
    {
        $this->smarty = $smarty;
        $this->userService = new UserService();
        $this->roleService = new RoleService();
        $this->userRoleRepository = new UserRoleRepository();
    }

    public function index(): void
    {
        $users = $this->userService->findAll();
        $this->smarty->assign("title", "Usuários");
        $this->smarty->assign("active_page", "users");
        $this->smarty->assign("users", $users);
        $this->smarty->display("users.tpl");
    }

    public function create(): void
    {
        $this->smarty->assign("title", "Novo Usuário");
        $this->smarty->assign("active_page", "users");
        $this->smarty->display("user_form.tpl");
    }

    public function store(): void
    {
        $username = $_POST["username"] ?? null;
        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;
        $cpf = $_POST["cpf"] ?? null;

        if ($username && $email && $password) {
            $user = $this->userService->createUser($username, $email, $password, $cpf);
            if ($user) {
                header("Location: /users");
                exit();
            }
        }
        $this->create();
    }

    public function edit(string $id): void
    {
        $user = $this->userService->findById($id);
        if (!$user) {
            header("Location: /users");
            exit();
        }
        $this->smarty->assign("title", "Editar Usuário");
        $this->smarty->assign("active_page", "users");
        $this->smarty->assign("user", $user);
        $this->smarty->display("user_form.tpl");
    }

    public function update(string $id): void
    {
        $user = $this->userService->findById($id);
        if ($user) {
            $user->username = $_POST["username"] ?? $user->username;
            $user->email = $_POST["email"] ?? $user->email;
            if (!empty($_POST["password"])) {
                $user->password_hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
            }
            $user->cpf = $_POST["cpf"] ?? $user->cpf;
            $user->enabled = isset($_POST["enabled"]) ? 1 : 0;
            $user->updated_at = time();
            $this->userService->userRepository->update($user);
        }
        header("Location: /users");
        exit();
    }

    public function delete(string $id): void
    {
        $this->userService->userRepository->delete($id);
        header("Location: /users");
        exit();
    }

    public function manageRoles(string $id): void
    {
        $user = $this->userService->findById($id);
        if (!$user) {
            header("Location: /users");
            exit();
        }

        $allRoles = $this->roleService->findAll();
        $userRoles = $this->userRoleRepository->findRolesByUserId($id);
        $userRoleIds = array_map(fn($r) => $r->id, $userRoles);

        $this->smarty->assign("title", "Gerenciar Papéis");
        $this->smarty->assign("active_page", "users");
        $this->smarty->assign("user", $user);
        $this->smarty->assign("all_roles", $allRoles);
        $this->smarty->assign("user_role_ids", $userRoleIds);
        $this->smarty->display("user_roles.tpl");
    }

    public function updateRoles(string $id): void
    {
        $selectedRoleIds = $_POST["roles"] ?? [];
        
        $currentRoles = $this->userRoleRepository->findRolesByUserId($id);
        foreach ($currentRoles as $role) {
            $this->userRoleRepository->removeRoleFromUser($id, $role->id);
        }

        foreach ($selectedRoleIds as $roleId) {
            $this->userRoleRepository->assignRoleToUser($id, $roleId);
        }

        header("Location: /users");
        exit();
    }
}
