<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Técnico - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --magenta: #F500C0;
            --cinza: #607575;
        }
        body { background: #f4f6f7; }
        .navbar { background: var(--magenta); }
        .navbar-brand { color: white; font-weight: bold; }
        .card-dashboard { border: none; border-radius: 12px; color: white; padding: 20px; background: var(--magenta); }
        .btn-criar { background: var(--magenta); color: white; border: none; }
        .btn-criar:hover { background: #c4009b; color: white; }
        .table thead { background: var(--cinza); color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand">Dicionário Técnico</a>
    </div>
</nav>

<div class="container mt-4">
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card-dashboard shadow text-center">
                <h5>Total de Termos (Únicos)</h5>
                <h2 id="totalTermos">0</h2>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Lista de Termos Técnicos</h4>
        <a href="create.php" class="btn btn-criar shadow-sm">
            <i class="bi bi-plus-lg"></i> Criar Termo Técnico
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Nome do Termo</th>
                        <th>Categoria</th>
                        <th>Descrição</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabelaCorpo">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', carregarTermos);

    async function carregarTermos() {
        try {
            const res = await fetch('api/api_termo_tecnico.php');
            const result = await res.json();
            
            if (result.success) {
                const tabela = document.getElementById('tabelaCorpo');
                document.getElementById('totalTermos').innerText = result.data.length;
                tabela.innerHTML = '';

                result.data.forEach(termo => {
                    tabela.innerHTML += `
                        <tr>
                            <td class="ps-4">#${termo.id_termo_tecnico}</td>
                            <td class="fw-bold">${termo.nome}</td>
                            <td><span class="badge bg-info text-dark">${termo.tipo_termo}</span></td>
                            <td class="text-truncate" style="max-width: 350px;">${termo.descricao_termo}</td>
                            <td class="text-center">
                                <a href="config_termo_tecnico_detalhes.php?id=${termo.id_termo_tecnico}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button class="btn btn-sm btn-danger" onclick="excluirTermo(${termo.id_termo_tecnico})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }
        } catch (error) {
            console.error("Erro ao carregar dados:", error);
        }
    }

    async function excluirTermo(id) {
        if(!confirm("Tem certeza que deseja excluir este termo?")) return;
        
        try {
            const res = await fetch('api/api_termo_tecnico.php', {
                method: 'DELETE',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id_termo_tecnico: id })
            });
            const result = await res.json();
            if(result.success) carregarTermos();
        } catch (e) { alert("Erro ao excluir."); }
    }
</script>
</body>
</html>