# Manual Técnico Definitivo - KCPocket PHP v1.0.10

Este manual fornece a documentação completa para integração com o KCPocket IAM, cobrindo autenticação de usuários, fluxos de redirecionamento (SSO) e validação de tokens via API.

---

## 1. Endpoints da API

| Endpoint | Método | Descrição |
|----------|--------|-----------|
| `/oauth2/token` | POST | Troca credenciais (usuário/senha ou app) por um Access Token (JWT). |
| `/oauth2/authorize` | GET | Inicia o fluxo de login centralizado e redirecionamento. |
| `/oauth2/introspect` | POST | **(Principal)** Valida um token e retorna os dados e permissões do usuário. |
| `/oauth2/jwks` | GET | Chaves públicas para sistemas que realizam validação local. |

---

## 2. Fluxos de Autenticação

### 2.1. Autenticação de Usuário via API (Password Grant)
Use este fluxo para validar as credenciais de um usuário específico e obter seu token de identidade.

**Exemplo cURL:**
```bash
curl -X POST http://localhost:8000/oauth2/token \
     -d "grant_type=password" \
     -d "username=admin@example.com" \
     -d "password=admin123" \
     -d "client_id=test-client-id" \
     -d "client_secret=test-client-secret"
```

### 2.2. Fluxo de Redirecionamento Web (SSO)
Para integrar o login em sua aplicação web:

1. **Redirecione o usuário para:**
   `http://localhost:8000/oauth2/authorize?client_id=test-client-id&redirect_uri=http://sua-app.com/callback&state=xyz`

2. **Sua aplicação receberá o token na URL de retorno:**
   `http://sua-app.com/callback?access_token=JWT_AQUI&state=xyz`

---

## 3. Validação do Token e Usuário (Introspecção)

Este é o método onde a aplicação cliente (em qualquer host) consulta o KCPocket para validar o token e identificar o usuário.

### Exemplo Completo em PHP 8

```php
<?php
/**
 * Script de Exemplo: Validação de Usuário e Token
 * Este script pode rodar em qualquer servidor com acesso ao KCPocket.
 */

function validarAcessoNoKCPocket($token) {
    $url = 'http://localhost:8000/oauth2/introspect';
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['token' => $token]);
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);
    
    // Retorna os dados se o token for válido e estiver ativo
    if (isset($data['active']) && $data['active'] === true) {
        return $data;
    }
    
    return null;
}

// --- CENÁRIO DE USO ---

$tokenRecebido = "COLE_O_TOKEN_AQUI"; // Token vindo do redirecionamento ou API
$dadosUsuario = validarAcessoNoKCPocket($tokenRecebido);

if ($dadosUsuario) {
    echo "Autenticação Confirmada!\n";
    echo "Usuário: " . $dadosUsuario['username'] . "\n";
    echo "ID (UUID): " . $dadosUsuario['sub'] . "\n";
    echo "Permissões Agregadas: " . implode(', ', $dadosUsuario['permissions']) . "\n";
} else {
    echo "Acesso Negado: Token inválido ou expirado.\n";
}
```

---

## 4. Exemplo de Autenticação de Usuário em PHP 8

Caso sua aplicação precise realizar o login do usuário programaticamente:

```php
<?php
/**
 * Script de Exemplo: Login de Usuário via API
 */

$url = 'http://localhost:8000/oauth2/token';
$postData = [
    'grant_type' => 'password',
    'username'   => 'admin@example.com',
    'password'   => 'admin123',
    'client_id'  => 'test-client-id',
    'client_secret' => 'test-client-secret'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

$response = curl_exec($ch);
$tokenInfo = json_decode($response, true);
curl_close($ch);

if (isset($tokenInfo['access_token'])) {
    echo "Login realizado! Token: " . $tokenInfo['access_token'];
} else {
    echo "Falha no login: " . ($tokenInfo['error_description'] ?? 'Erro desconhecido');
}
```

---

## 5. Estrutura de Resposta da Introspecção (JSON)

Ao validar um token, o KCPocket retorna os detalhes do usuário e suas permissões RBAC:

```json
{
  "active": true,
  "username": "admin",
  "sub": "00000000-0000-0000-0000-000000000001",
  "permissions": [
    "READ_PRIVILEGE",
    "WRITE_PRIVILEGE"
  ],
  "exp": 1713964800,
  "iat": 1713961200
}
```
