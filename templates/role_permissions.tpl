{extends file="layout.tpl"}

{block name="content"}
    <h2>Gerenciar Permissões: {$role->name}</h2>
    <p class="text-muted">Selecione as permissões que deseja atribuir a este papel.</p>

    <form action="/roles/{$role->id}/permissions" method="POST">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    {if !empty($all_permissions)}
                        {foreach $all_permissions as $permission}
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{$permission->id}" id="perm_{$permission->id}" 
                                        {if in_array($permission->id, $role_permission_ids)}checked{/if}>
                                    <label class="form-check-label" for="perm_{$permission->id}">
                                        <strong>{$permission->name}</strong><br>
                                        <small class="text-muted">{$permission->description}</small>
                                    </label>
                                </div>
                            </div>
                        {/foreach}
                    {else}
                        <div class="col-12">
                            <p class="text-muted">Nenhuma permissão cadastrada no sistema.</p>
                        </div>
                    {/if}
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Associações</button>
        <a href="/roles" class="btn btn-secondary">Voltar</a>
    </form>
{/block}
