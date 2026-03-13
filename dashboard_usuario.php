<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Técnico - Dashboard Usuário</title>
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
        .card-dashboard { border: none; border-radius: 12px; color: white; padding: 20px; }
        .card-magenta { background: var(--magenta); }
        .btn-criar { background: var(--magenta); color: white; border: none; }
        .btn-criar:hover { background: #c4009b; color: white; }
        .table thead { background: var(--cinza); color: white; }

        .badge-aprovado { background-color: #198754; color: #fff; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-book me-2"></i>Dicionário Técnico - Usuário
            </a>
            <div class="d-flex align-items-center">
                <a href="api/logout.php" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-dashboard card-magenta text-center shadow-sm">
                    <h5>Termos Disponíveis</h5>
                    <h2 id="totalTermos">0</h2>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Dicionário de Termos Técnicos</h4>
            <a href="create.php" class="btn btn-criar shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Sugerir Novo Termo
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">ID</th>
                                <th>Nome do Termo</th>
                                <th>Descrição</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaTermo">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        async function carregarTermos() {
            const tabela = document.getElementById("tabelaTermo");
            const contador = document.getElementById("totalTermos");

            try {
                // Buscamos todos os termos da API
                const response = await fetch("api/api_termo_tecnico.php");
                const resultado = await response.json();

                if (resultado.success) {
                    tabela.innerHTML = "";
                    
                    // FILTRO: Filtramos apenas os termos que tem o status "Aprovado"
                    const termosAprovados = resultado.data.filter(termo => termo.status === "Aprovado");
                    
                    // Atualiza o contador com a quantidade de aprovados
                    contador.innerText = termosAprovados.length;

                    if(termosAprovados.length === 0) {
                        tabela.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-muted">Nenhum termo técnico aprovado no momento.</td></tr>`;
                        return;
                    }

                    termosAprovados.forEach(termo => {
                        // Sincronizando com as colunas do seu banco
                        const id = termo.idtermos; 
                        const nome = termo.nome_termo;
                        const desc = termo.descricao_termo;

                        tabela.innerHTML += `
                        <tr>
                            <td class="ps-3 text-muted">#${id}</td>
                            <td><strong>${nome}</strong></td>
                            <td>${desc}</td>
                            <td class="text-center">
                                <span class="badge badge-aprovado p-2 shadow-sm" style="min-width: 110px;">
                                    <i class="bi bi-check-circle me-1"></i> Aprovado
                                </span>
                            </td>
                        </tr>`;
                    });
                }
            } catch (erro) {
                console.error("Erro ao carregar termos:", erro);
                tabela.innerHTML = `<tr><td colspan="4" class="text-center text-danger p-3">Erro ao conectar com o servidor.</td></tr>`;
            }
        }

        // Inicia a carga de dados
        carregarTermos();
    </script>
</body>
</html>