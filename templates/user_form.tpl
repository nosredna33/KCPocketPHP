{extends file="layout.tpl"}

{block name="content"}
    <h2>{if isset($user->id)}Editar Usuário{else}Novo Usuário{/if}</h2>

    <form action="{if isset($user->id)}/users/{$user->id}/edit{else}/users/create{/if}" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{if isset($user->username)}{$user->username}{/if}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{if isset($user->email)}{$user->email}{/if}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Senha {if isset($user->id)}(Deixe em branco para não alterar){/if}</label>
            <div class="input-group">
                <input type="text" class="form-control" id="password" name="password" {if !isset($user->id)}required{/if}>
                <button class="btn btn-outline-secondary" type="button" onclick="generatePassword('password')">Gerar Senha</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="cpf" class="form-label">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="{if isset($user->cpf)}{$user->cpf}{/if}">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="enabled" name="enabled" value="1" {if isset($user->enabled) && $user->enabled}checked{/if}>
            <label class="form-check-label" for="enabled">Ativo</label>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/users" class="btn btn-secondary">Cancelar</a>
    </form>

    <script>
        function generatePassword(targetId) {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let password = "";
            for (let i = 0; i < 12; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            document.getElementById(targetId).value = password;
        }
    </script>
{/block}
