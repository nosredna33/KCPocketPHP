<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../config/smarty_config.php";

use KCPocket\Util\Router;
use KCPocket\Controller\WebController;
use KCPocket\Controller\AdminUserController;
use KCPocket\Controller\AdminRoleController;
use KCPocket\Controller\AdminPermissionController;
use KCPocket\Controller\AdminClientController;
use KCPocket\Controller\OAuth2Controller;
use KCPocket\Controller\AuthController;

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize Smarty
$smarty = getSmarty();

// Check Authentication for all routes except login and some OAuth2 endpoints
AuthController::checkAuth();

// Auth Controllers
Router::get("/login", function() use ($smarty) { (new AuthController($smarty))->showLogin(); });
Router::post("/login", function() use ($smarty) { (new AuthController($smarty))->login(); });
Router::get("/logout", function() use ($smarty) { (new AuthController($smarty))->logout(); });

// Web Controllers
Router::get("/", function() use ($smarty) { (new WebController($smarty))->dashboard(); });
Router::get("/dashboard", function() use ($smarty) { (new WebController($smarty))->dashboard(); });

// User Admin Controllers
Router::get("/users", function() use ($smarty) { (new AdminUserController($smarty))->index(); });
Router::get("/users/create", function() use ($smarty) { (new AdminUserController($smarty))->create(); });
Router::post("/users/create", function() use ($smarty) { (new AdminUserController($smarty))->store(); });
Router::get("/users/{id}/edit", function($id) use ($smarty) { (new AdminUserController($smarty))->edit($id); });
Router::post("/users/{id}/edit", function($id) use ($smarty) { (new AdminUserController($smarty))->update($id); });
Router::get("/users/{id}/delete", function($id) use ($smarty) { (new AdminUserController($smarty))->delete($id); });
Router::get("/users/{id}/roles", function($id) use ($smarty) { (new AdminUserController($smarty))->manageRoles($id); });
Router::post("/users/{id}/roles", function($id) use ($smarty) { (new AdminUserController($smarty))->updateRoles($id); });

// Role Admin Controllers
Router::get("/roles", function() use ($smarty) { (new AdminRoleController($smarty))->index(); });
Router::get("/roles/create", function() use ($smarty) { (new AdminRoleController($smarty))->create(); });
Router::post("/roles/create", function() use ($smarty) { (new AdminRoleController($smarty))->store(); });
Router::get("/roles/{id}/edit", function($id) use ($smarty) { (new AdminRoleController($smarty))->edit($id); });
Router::post("/roles/{id}/edit", function($id) use ($smarty) { (new AdminRoleController($smarty))->update($id); });
Router::get("/roles/{id}/delete", function($id) use ($smarty) { (new AdminRoleController($smarty))->delete($id); });
Router::get("/roles/{id}/permissions", function($id) use ($smarty) { (new AdminRoleController($smarty))->managePermissions($id); });
Router::post("/roles/{id}/permissions", function($id) use ($smarty) { (new AdminRoleController($smarty))->updatePermissions($id); });

// Permission Admin Controllers
Router::get("/permissions", function() use ($smarty) { (new AdminPermissionController($smarty))->index(); });
Router::get("/permissions/create", function() use ($smarty) { (new AdminPermissionController($smarty))->create(); });
Router::post("/permissions/create", function() use ($smarty) { (new AdminPermissionController($smarty))->store(); });
Router::get("/permissions/{id}/edit", function($id) use ($smarty) { (new AdminPermissionController($smarty))->edit($id); });
Router::post("/permissions/{id}/edit", function($id) use ($smarty) { (new AdminPermissionController($smarty))->update($id); });
Router::get("/permissions/{id}/delete", function($id) use ($smarty) { (new AdminPermissionController($smarty))->delete($id); });

// Client Admin Controllers
Router::get("/clients", function() use ($smarty) { (new AdminClientController($smarty))->index(); });
Router::get("/clients/create", function() use ($smarty) { (new AdminClientController($smarty))->create(); });
Router::post("/clients/create", function() use ($smarty) { (new AdminClientController($smarty))->store(); });
Router::get("/clients/{id}/edit", function($id) use ($smarty) { (new AdminClientController($smarty))->edit($id); });
Router::post("/clients/{id}/edit", function($id) use ($smarty) { (new AdminClientController($smarty))->update($id); });
Router::get("/clients/{id}/delete", function($id) use ($smarty) { (new AdminClientController($smarty))->delete($id); });

// OAuth2 Endpoints
Router::get("/oauth2/authorize", function() use ($smarty) { (new OAuth2Controller($smarty))->authorize(); });
Router::get("/oauth2/curl", function() use ($smarty) { (new OAuth2Controller($smarty))->curlInfo(); });
Router::get("/oauth2/jwks", function() use ($smarty) { (new OAuth2Controller($smarty))->jwks(); });
Router::post("/oauth2/token", function() use ($smarty) { (new OAuth2Controller($smarty))->token(); });
Router::post("/oauth2/introspect", function() use ($smarty) { (new OAuth2Controller($smarty))->introspect(); });

// Dispatch the request
Router::dispatch();
