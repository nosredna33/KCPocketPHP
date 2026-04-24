{extends file="layout.tpl"}

{block name="content"}
    <h2>Gerenciar Papéis: {$user->username}</h2>
    <p class="text-muted">Selecione os papéis que deseja atribuir a este usuário.</p>

    <form action="/users/{$user->id}/roles" method="POST">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    {foreach $all_roles as $role}
                        <div class="col-md-4 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="roles[]" value="{$role->id}" id="role_{$role->id}" 
                                    {if in_array($role->id, $user_role_ids)}checked{/if}>
                                <label class="form-check-label" for="role_{$role->id}">
                                    <strong>{$role->name}</strong><br>
                                    <small class="text-muted">{$role->description}</small>
                                </label>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Associações</button>
        <a href="/users" class="btn btn-secondary">Voltar</a>
    </form>
{/block}
