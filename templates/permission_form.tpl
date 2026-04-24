{extends file="layout.tpl"}

{block name="content"}
    <h2>{if isset($permission->id)}Editar Permissão{else}Nova Permissão{/if}</h2>

    <form action="{if isset($permission->id)}/permissions/{$permission->id}/edit{else}/permissions/create{/if}" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{if isset($permission->name)}{$permission->name}{/if}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Descrição</label>
            <textarea class="form-control" id="description" name="description">{if isset($permission->description)}{$permission->description}{/if}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/permissions" class="btn btn-secondary">Cancelar</a>
    </form>
{/block}
