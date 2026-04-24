{extends file="layout.tpl"}

{block name="content"}
    <h2>Usuários</h2>
    <a href="/users/create" class="btn btn-primary mb-3">Novo Usuário</a>

    {if !empty($users)}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>CPF</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                {foreach $users as $user}
                    <tr>
                        <td><small>{$user->id}</small></td>
                        <td>{$user->username}</td>
                        <td>{$user->email}</td>
                        <td>{if $user->cpf}{$user->cpf}{else}<span class="text-muted">N/A</span>{/if}</td>
                        <td>
                            {if $user->enabled}
                                <span class="badge bg-success">Sim</span>
                            {else}
                                <span class="badge bg-danger">Não</span>
                            {/if}
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="/users/{$user->id}/edit" class="btn btn-sm btn-info" title="Editar"><i class="bi bi-pencil"></i></a>
                                <a href="/users/{$user->id}/roles" class="btn btn-sm btn-warning" title="Papéis"><i class="bi bi-person-badge"></i></a>
                                <a href="/users/{$user->id}/delete" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza?')"><i class="bi bi-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <div class="alert alert-info">Nenhum usuário encontrado.</div>
    {/if}
{/block}
