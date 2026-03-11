<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - termo_tecnico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body>
    <header>
        <nav class="navbar mb-4 shadow-sm" data-bs-theme="dark" style="background-color: #1a237e; border-bottom: 1px solid #283593;">
            <div class="container">
                <a class="navbar-brand" href="#">SGM - Gerenciamento de termo_tecnico</a>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-white">Olá, <?php echo $_SESSION['user_nome'] ?? 'Usuário'; ?></span>
                    <a href="api/logout.php" class="btn btn-outline-light btn-sm">Sair</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>termo_tecnico</h1> 
            <a href="config_criar_termo_tecnico.php" class="btn btn-success">+ Novo termo_tecnico</a>
        </div>

        <div class="table-responsive shadow-sm">
            <table class="table table-hover border">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome termo_tecnico</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Deletar</th>
                    </tr>
                </thead>
                <tbody id="tabela-termo_tecnico"></tbody> 
            </table>
        </div>
    </main>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        carregar_termo_tecnico();
    });

    async function carregar_termo_tecnico() {
        try {
            const response = await fetch('api/api_termo_tecnico.php'); 
            const result = await response.json();
            const tbody = document.getElementById('tabela-termo_tecnico');

            if (result.success) {
                tbody.innerHTML = result.data.map(item => `
                    <tr>
                        <th scope="row">${item.id_termo_tecnico}</th>
                        <td>
                            <strong>${item.nome}</strong> 
                            <span class="badge bg-secondary ms-2">${item.nome_bloco}</span>
                        </td>
                        <td>
                            <a href="config_termo_tecnico_detalhes.php?id=${item.id_termo_tecnico}" class="btn btn-primary btn-sm">
                                Gerenciar
                            </a>
                        </td>
                        <td>
                            <button onclick="excluir_termo_tecnico(${item.id_termo_tecnico})" class="btn btn-danger btn-sm">
                                Deletar
                            </button>
                        </td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger">${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Erro ao buscar dados:', error);
            document.getElementById('tabela-termo_tecnico').innerHTML = `<tr><td colspan="4" class="text-center">Erro ao conectar com a API</td></tr>`;
        }
    }

    async function excluir_termo_tecnico(id) {
        if (confirm('Tem certeza que deseja excluir este termo_tecnico?')) {
            try {
                const response = await fetch('api/termo_tecnico.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_termo_tecnico: id })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    carregar_termo_tecnico();
                } else {
                    alert('Erro: ' + result.message);
                }
            } catch (error) {
                alert('Erro na comunicação com o servidor.');
            }
        }
    }
</script>
</body>
</html>