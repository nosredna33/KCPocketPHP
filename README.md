# KCPocket PHP

Este é um projeto de portabilidade do sistema KCPocket (originalmente em Java 21 + Spring Boot 3 + Thymeleaf) para PHP 8 + Smarty + SQLite 3. O objetivo é manter todas as funcionalidades de OAuth2/OpenID Connect, RBAC (Role-Based Access Control) e o painel administrativo.

## Requisitos

- PHP 8.1 ou superior
- Extensões PHP: `pdo_sqlite`, `curl`, `mbstring`, `openssl`
- Composer
- SQLite 3

## Instalação

1.  **Clone o repositório (ou descompacte o arquivo do projeto):**

    ```bash
    git clone <URL_DO_REPOSITORIO>
    cd kcpocket_php
    ```

2.  **Instale as dependências do Composer:**

    ```bash
    composer install
    ```

3.  **Inicialize o banco de dados SQLite:**

    ```bash
    php init-db.php
    ```

    Este script criará o arquivo `kcpocket.db` no diretório `data/` e populará com dados iniciais (usuários, papéis, permissões).

4.  **Gere as chaves RSA para JWT:**

    As chaves privada e pública serão geradas automaticamente na primeira vez que o `JwtKeyProvider` for instanciado, ou você pode gerá-las manualmente:

    ```bash
    mkdir -p data
    openssl genrsa -out data/private_key.pem 2048
    openssl rsa -in data/private_key.pem -pubout -out data/public_key.pem
    ```

## Uso

### Iniciar o Servidor Web Embutido do PHP

Para iniciar a aplicação rapidamente para desenvolvimento:

```bash
./start_server.sh
```

O servidor estará disponível em `http://localhost:8000`.

### Acessar o Painel Administrativo

Navegue para `http://localhost:8000/dashboard`.

**Credenciais de Teste (podem variar, verificar `init-db.php`):**

-   **Usuário:** `admin@example.com`
-   **Senha:** `admin123`

### Endpoints OAuth2/OpenID Connect

-   **JWKS Endpoint:** `http://localhost:8000/oauth2/jwks`
-   **Token Endpoint:** `http://localhost:8000/oauth2/token`

Você pode visualizar exemplos de comandos cURL para os endpoints OAuth2 em `http://localhost:8000/oauth2/curl`.

## Estrutura do Projeto

```
. (kcpocket_php)
├── public/
│   └── index.php           # Ponto de entrada da aplicação
│   └── .htaccess           # Regras de reescrita de URL
├── src/
│   ├── Controller/         # Controladores para as rotas web e API
│   ├── Model/              # Modelos de dados
│   ├── Repository/         # Camada de acesso a dados
│   ├── Security/           # Classes relacionadas à segurança (JWT, chaves RSA)
│   ├── Service/            # Lógica de negócio e serviços
│   └── Util/               # Utilitários (Router, Database)
├── templates/              # Arquivos de template Smarty (.tpl)
├── templates_c/            # Diretório de templates compilados pelo Smarty
├── cache/                  # Diretório de cache do Smarty
├── config/
│   └── smarty_config.php   # Configuração do Smarty
├── data/
│   └── kcpocket.db         # Banco de dados SQLite
│   └── private_key.pem     # Chave privada RSA para JWT
│   └── public_key.pem      # Chave pública RSA para JWT
├── vendor/                 # Dependências do Composer
├── composer.json           # Definições de dependências do Composer
├── composer.lock           # Lock file do Composer
├── init-db.php             # Script de inicialização do banco de dados
├── start_server.sh         # Script para iniciar o servidor PHP embutido
├── test_system.php         # Script para testes funcionais básicos
└── README.md               # Este arquivo
```

## Testes

Para executar os testes funcionais básicos:

```bash
php test_system.php
```

## Contribuição

Sinta-se à vontade para contribuir com melhorias, correções de bugs ou novas funcionalidades. Por favor, abra uma issue ou envie um pull request.

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes. (Nota: O arquivo LICENSE não foi gerado neste processo, mas seria incluído em um projeto real.)
