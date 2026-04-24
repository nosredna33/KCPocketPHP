{extends file="layout.tpl"}

{block name="content"}
    <h2>Clientes OAuth</h2>
    <a href="/clients/create" class="btn btn-primary mb-3">Novo Cliente OAuth</a>

    {if !empty($clients)}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client ID</th>
                    <th>Redirect URI</th>
                    <th>Scopes</th>
                    <th>Grant Types</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                {foreach $clients as $client}
                    <tr>
                        <td>{$client->id}</td>
                        <td>{$client->client_id}</td>
                        <td>{$client->redirect_uri}</td>
                        <td>{$client->scopes}</td>
                        <td>{$client->grant_types}</td>
                        <td>
                            <a href="/clients/{$client->id}/edit" class="btn btn-sm btn-info"><i class="bi bi-pencil"></i> Editar</a>
                            <a href="/clients/{$client->id}/delete" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Excluir</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <div class="alert alert-info">Nenhum cliente OAuth encontrado.</div>
    {/if}
{/block}
