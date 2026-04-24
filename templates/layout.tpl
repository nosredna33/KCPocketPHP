<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title|default:'KCPocket PHP'}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        .wrapper { display: flex; flex: 1; }
        #sidebar { min-width: 250px; max-width: 250px; background: #343a40; color: #fff; transition: all 0.3s; }
        #sidebar.active { margin-left: -250px; }
        #content { flex: 1; padding: 20px; }
        .sidebar-header { padding: 20px; background: #212529; }
        .list-unstyled.components li a { padding: 10px 20px; color: #adb5bd; display: block; text-decoration: none; }
        .list-unstyled.components li a:hover { color: #fff; background: #495057; }
        .active > a { color: #fff !important; background: #0d6efd !important; }
        @media (max-width: 768px) { #sidebar { margin-left: -250px; } #sidebar.active { margin-left: 0; } }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>KCPocket PHP</h3>
            </div>
            <ul class="list-unstyled components">
                <li class="{if $active_page == 'dashboard'}active{/if}">
                    <a href="/dashboard"><i class="bi bi-house-door"></i> Dashboard</a>
                </li>
                <li class="{if $active_page == 'users'}active{/if}">
                    <a href="/users"><i class="bi bi-people"></i> Usuários</a>
                </li>
                <li class="{if $active_page == 'roles'}active{/if}">
                    <a href="/roles"><i class="bi bi-person-badge"></i> Papéis</a>
                </li>
                <li class="{if $active_page == 'permissions'}active{/if}">
                    <a href="/permissions"><i class="bi bi-key"></i> Permissões</a>
                </li>
                <li class="{if $active_page == 'clients'}active{/if}">
                    <a href="/clients"><i class="bi bi-app"></i> Clientes OAuth</a>
                </li>
                <li>
                    <a href="/logout" class="text-danger"><i class="bi bi-box-arrow-right"></i> Sair</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="ms-auto">
                        <span class="navbar-text me-3">
                            Bem-vindo, <strong>{$smarty.session.username|default:'Usuário'}</strong>!
                        </span>
                        <a href="/oauth2/curl" class="btn btn-outline-secondary btn-sm">API Docs</a>
                    </div>
                </div>
            </nav>

            {block name="content"}{/block}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("sidebarCollapse").addEventListener("click", function () {
            document.getElementById("sidebar").classList.toggle("active");
        });
    </script>
</body>
</html>
