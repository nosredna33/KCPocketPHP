<?php
/**
 * Exemplo de Callback (Aplicação Cliente)
 * 
 * Este script simula uma aplicação externa recebendo o redirecionamento do IAM.
 */

$token = $_GET['access_token'] ?? null;
$state = $_GET['state'] ?? null;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Callback Example - Aplicação Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container bg-white p-4 rounded shadow">
        <h2 class="text-success">Sucesso! Redirecionamento Recebido</h2>
        <p class="lead">A aplicação cliente recebeu o token do KCPocket IAM.</p>
        
        <div class="alert alert-info">
            <strong>Token Recebido:</strong>
            <pre class="mt-2" style="white-space: pre-wrap; word-break: break-all;"><code><?php echo htmlspecialchars($token); ?></code></pre>
        </div>

        <?php if ($state): ?>
            <p><strong>Estado (State):</strong> <?php echo htmlspecialchars($state); ?></p>
        <?php endif; ?>

        <hr>
        <h5>Próximos Passos para a Aplicação Cliente:</h5>
        <ol>
            <li>Validar a assinatura do token usando o endpoint <code>/oauth2/jwks</code>.</li>
            <li>Extrair as permissões do usuário para autorizar ações.</li>
            <li>Iniciar a sessão local do usuário.</li>
        </ol>
        
        <a href="/dashboard" class="btn btn-primary">Voltar ao IAM</a>
    </div>
</body>
</html>
