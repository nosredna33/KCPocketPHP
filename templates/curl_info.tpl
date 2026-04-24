{extends file="layout.tpl"}

{block name="content"}
    <h2>Informações cURL para {$endpoint_name|default:"API Endpoint"}</h2>

    {if !empty($curl_commands)}
        {foreach $curl_commands as $command_group_name => $commands}
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">{$command_group_name}</h4>
                </div>
                <div class="card-body">
                    {foreach $commands as $command}
                        <div class="mb-3">
                            <pre class="bg-dark text-light p-3 rounded"><code>{$command}</code></pre>
                        </div>
                    {foreachelse}
                        <p class="text-muted">Nenhum comando disponível neste grupo.</p>
                    {/foreach}
                </div>
            </div>
        {/foreach}
    {else}
        <div class="alert alert-info">Nenhum comando cURL disponível para este endpoint.</div>
    {/if}
{/block}
