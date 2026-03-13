<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Técnico - Professor</title>
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
        
        .btn-editar { background-color: var(--amarelo); color: black; border: none; }
        .btn-editar:hover { background-color: #d4c73d; color: black; }

        /* Estilização dos Badges de Status */
        .badge-espera { background-color: #ffc107; color: #000; } /* Amarelo */
        .badge-aprovado { background-color: #198754; color: #fff; } /* Verde */
        .badge-reprovado { background-color: #dc3545; color: #fff; } /* Vermelho */
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Dicionário Técnico - Professor</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-dashboard card-magenta text-center">
                    <h5>Total de Termos</h5>
                    <h2 id="totalTermos">0</h2>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Gestão de Termos (Avaliação do Professor)</h4>
            <a href="create.php" class="btn btn-criar">
                <i class="bi bi-plus-circle me-1"></i> Criar Termo Técnico
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Nome do Termo</th>
                            <th>Descrição</th>
                            <th class="text-center">Status Atual</th>
                            <th class="text-center">Ações de Avaliação</th>
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
                        // Lógica para definir a cor do badge baseado no status vindo do banco
                        let badgeClass = "badge-espera";
                        let statusTexto = termo.status || "Em Espera";

                        if(statusTexto === "Aprovado") badgeClass = "badge-aprovado";
                        if(statusTexto === "Reprovado") badgeClass = "badge-reprovado";

                        tabela.innerHTML += `
                        <tr>
                            <td class="ps-3">#${termo.id_termo_tecnico}</td>
                            <td><strong>${termo.nome}</strong></td>
                            <td>${termo.descricao_termo}</td>
                            <td class="text-center">
                                <span class="badge ${badgeClass}">${statusTexto}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <button onclick="alterarStatus(${termo.id_termo_tecnico}, 'Aprovado')" class="btn btn-sm btn-success" title="Aprovar">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    <button onclick="alterarStatus(${termo.id_termo_tecnico}, 'Reprovado')" class="btn btn-sm btn-danger" title="Reprovar">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <a href="update.php?id=${termo.id_termo_tecnico}" class="btn btn-sm btn-editar" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        `;
                    });
                }
            } catch (erro) {
                console.error("Erro ao carregar termos:", erro);
            }
        }

        // Função para o professor decidir o status
        async function alterarStatus(id, novoStatus) {
            if(confirm(`Deseja alterar o status para ${novoStatus}?`)) {
                try {
                    // Aqui você deve ajustar para o endpoint correto da sua API que trata o status
                    const response = await fetch("http://localhost/2025/termo_tecnico/api/api_termo_tecnico.php", {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            id_termo_tecnico: id,
                            status: novoStatus
                        })
                    });
                    
                    const res = await response.json();
                    if(res.success) {
                        alert("Status atualizado!");
                        carregarTermos(); // Recarrega a lista
                    }
                } catch (error) {
                    console.error("Erro ao atualizar status:", error);
                }
            }
        }

        carregarTermos();
    </script>
</body>
</html>