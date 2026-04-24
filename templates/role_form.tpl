{extends file="layout.tpl"}

{block name="content"}
    <h2>{if isset($role->id)}Editar Papel{else}Novo Papel{/if}</h2>

    <form action="{if isset($role->id)}/roles/{$role->id}/edit{else}/roles/create{/if}" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{if isset($role->name)}{$role->name}{/if}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea class="form-control" id="description" name="description">{if isset($role->description)}{$role->description}{/if}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/roles" class="btn btn-secondary">Cancelar</a>
    </form>
{/block}
