{extends file="layout.tpl"}

{block name="content"}
    <h2>Permissões</h2>
    <a href="/permissions/create" class="btn btn-primary mb-3">Nova Permissão</a>

    {if !empty($permissions)}
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
                {foreach $permissions as $permission}
                    <tr>
                        <td>{$permission->id}</td>
                        <td>{$permission->name}</td>
                        <td>{if $permission->description}{$permission->description}{else}<span class="text-muted">N/A</span>{/if}</td>
                        <td>
                            <a href="/permissions/{$permission->id}/edit" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> Editar</a>
                            <a href="/permissions/{$permission->id}/delete" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <div class="alert alert-info">Nenhuma permissão encontrada.</div>
    {/if}
{/block}
