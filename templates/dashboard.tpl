{extends file="layout.tpl"}

{block name="content"}
    <h2>Dashboard</h2>
    <p>Bem-vindo ao painel administrativo do KCPocket PHP.</p>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Usuários</div>
                <div class="card-body">
                    <h5 class="card-title">{$user_count|default:0}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Papéis</div>
                <div class="card-body">
                    <h5 class="card-title">{$role_count|default:0}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Permissões</div>
                <div class="card-body">
                    <h5 class="card-title">{$permission_count|default:0}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Clientes OAuth</div>
                <div class="card-body">
                    <h5 class="card-title">{$client_count|default:0}</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informações do Sistema</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Versão PHP: <strong>{$php_version}</strong></li>
                        <li class="list-group-item">Versão Smarty: <strong>{$smarty_version}</strong></li>
                        <li class="list-group-item">Banco de Dados: <strong>SQLite 3</strong></li>
                        <li class="list-group-item">Status do JWT: <strong>Chaves RSA {if $jwt_keys_exist}existentes{else}geradas{/if}</strong></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Demonstração RBAC (Admin)</h5>
                </div>
                <div class="card-body">
                    <p>Permissões agregadas do usuário <strong>admin</strong> (herdadas de seus papéis):</p>
                    {if !empty($admin_permissions)}
                        <div class="d-flex flex-wrap gap-2">
                            {foreach $admin_permissions as $perm}
                                <span class="badge bg-secondary">{$perm->name}</span>
                            {/foreach}
                        </div>
                    {else}
                        <p class="text-muted">Nenhuma permissão encontrada.</p>
                    {/if}
                </div>
            </div>
        </div>
    </div>
{/block}
