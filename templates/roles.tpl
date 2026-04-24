{extends file="layout.tpl"}

{block name="content"}
    <h2>Papéis</h2>
    <a href="/roles/create" class="btn btn-primary mb-3">Novo Papel</a>

    {if !empty($roles)}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                {foreach $roles as $role}
                    <tr>
                        <td>{$role->id}</td>
                        <td>{$role->name}</td>
                        <td>{if $role->description}{$role->description}{else}<span class="text-muted">N/A</span>{/if}</td>
                        <td>
                            <a href="/roles/{$role->id}/edit" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> Editar</a>
                            <a href="/roles/{$role->id}/delete" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Excluir</a>
                            <a href="/roles/{$role->id}/permissions" class="btn btn-sm btn-secondary"><i class="bi bi-key"></i> Gerenciar Permissões</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <div class="alert alert-info">Nenhum papel encontrado.</div>
    {/if}
{/block}
