{extends file="layout.tpl"}

{block name="content"}
    <h2>{if isset($client->id)}Editar Cliente OAuth{else}Novo Cliente OAuth{/if}</h2>

    <form action="{if isset($client->id)}/clients/{$client->id}/edit{else}/clients/create{/if}" method="POST">
        <div class="mb-3">
            <label for="client_id" class="form-label">Client ID</label>
            <div class="input-group">
                <input type="text" class="form-control" id="client_id" name="client_id" value="{if isset($client->client_id)}{$client->client_id}{/if}" required>
                <button class="btn btn-outline-secondary" type="button" onclick="generateUUID('client_id')">Gerar UUID</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="client_secret" class="form-label">Client Secret {if isset($client->id)}(Deixe em branco para não alterar){/if}</label>
            <div class="input-group">
                <input type="text" class="form-control" id="client_secret" name="client_secret" value="" {if !isset($client->id)}required{/if}>
                <button class="btn btn-outline-secondary" type="button" onclick="generateSecret('client_secret')">Gerar Secret</button>
            </div>
        </div>
        <div class="mb-3">
            <label for="redirect_uri" class="form-label">Redirect URI</label>
            <input type="text" class="form-control" id="redirect_uri" name="redirect_uri" value="{if isset($client->redirect_uri)}{$client->redirect_uri}{/if}" required>
        </div>
        <div class="mb-3">
            <label for="scopes" class="form-label">Scopes (separados por espaço)</label>
            <input type="text" class="form-control" id="scopes" name="scopes" value="{if isset($client->scopes)}{$client->scopes}{else}openid profile email{/if}" required>
        </div>
        <div class="mb-3">
            <label for="grant_types" class="form-label">Grant Types (separados por espaço)</label>
            <input type="text" class="form-control" id="grant_types" name="grant_types" value="{if isset($client->grant_types)}{$client->grant_types}{else}authorization_code refresh_token{/if}" required>
        </div>
        <div class="mb-3">
            <label for="access_token_ttl" class="form-label">Access Token TTL (segundos)</label>
            <input type="number" class="form-control" id="access_token_ttl" name="access_token_ttl" value="{if isset($client->access_token_ttl)}{$client->access_token_ttl}{else}3600{/if}" required>
        </div>
        <div class="mb-3">
            <label for="refresh_token_ttl" class="form-label">Refresh Token TTL (segundos)</label>
            <input type="number" class="form-control" id="refresh_token_ttl" name="refresh_token_ttl" value="{if isset($client->refresh_token_ttl)}{$client->refresh_token_ttl}{else}604800{/if}" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="/clients" class="btn btn-secondary">Cancelar</a>
    </form>

    <script>
        function generateUUID(targetId) {
            const uuid = crypto.randomUUID();
            document.getElementById(targetId).value = uuid;
        }

        function generateSecret(targetId) {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
            let secret = "";
            for (let i = 0; i < 32; i++) {
                secret += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            document.getElementById(targetId).value = secret;
        }
    </script>
{/block}
