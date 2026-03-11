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
            --ciano: #4EE5F0;
            --amarelo: #E5D84E;
            --cinza: #607575;
            --roxo: #7B3D6E;
        }

        body { background: #f4f6f7; }
        .navbar { background: var(--magenta); }
        .navbar-brand { color: white; font-weight: bold; }
        .card-dashboard { border: none; border-radius: 12px; color: white; padding: 20px; }
        .card-magenta { background: var(--magenta); }
        .btn-criar { background: var(--magenta); color: white; border: none; }
        .btn-criar:hover { background: #c4009b; color: white; }
        .table thead { background: var(--cinza); color: white; }
        
        /* Ajuste para o botão de editar ocupar bem o espaço */
        .btn-editar { background-color: var(--amarelo); color: black; border: none; }
        .btn-editar:hover { background-color: #d4c73d; color: black; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Dicionário Técnico</a>
    </div>
</nav>

<div class="container mt-4">

<!-- CARDS -->

<div class="row g-3 mb-4">

<div class="col-md-4">
<div class="card-dashboard card-magenta text-center">
<h5>Total de Termos</h5>
<h2 id="totalTermos">0</h2>
</div>
</div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Lista de Termos Técnicos</h4>
        <a href="create.php" class="btn btn-criar">
            <i class="bi bi-plus-circle me-1"></i> Criar Termo Técnico
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Nome do Termo</th>
                        <th>Descrição</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabelaTermo">
                    </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<script>
async function carregarTermos() {
    try {
        const response = await fetch("http://localhost/2025/termo_tecnico/api/api_termo_tecnico.php");
        const resultado = await response.json();

        if (resultado.success) {
            const tabela = document.getElementById("tabelaTermo");
            const contador = document.getElementById("totalTermos");
            
            tabela.innerHTML = "";
            contador.innerText = resultado.data.length;

            resultado.data.forEach(termo => {
                tabela.innerHTML += `
                <tr>
                    <td class="ps-3">#${termo.id_termo_tecnico}</td>
                    <td><strong>${termo.nome}</strong></td>
                    <td>${termo.descricao_termo}</td>
                    <td class="text-center">
                        <a href="update.php?id=${termo.id_termo_tecnico}" class="btn btn-sm btn-editar shadow-sm">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                    </td>
                </tr>
                `;
            });
        }
    } catch (erro) {
        console.error("Erro ao carregar termos:", erro);
    }
}

carregarTermos();
</script>

</body>
</html>