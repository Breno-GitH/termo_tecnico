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

        /* Cores dos Badges de Status */
        .badge-espera { background-color: #ffc107; color: #000; }   /* Amarelo */
        .badge-aprovado { background-color: #198754; color: #fff; } /* Verde */
        .badge-reprovado { background-color: #dc3545; color: #fff; } /* Vermelho */
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Dicionário Técnico - Usuário</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card-dashboard card-magenta text-center">
                    <h5>Meus Termos Enviados</h5>
                    <h2 id="totalTermos">0</h2>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Acompanhamento de Sugestões</h4>
            <a href="create.php" class="btn btn-criar">
                <i class="bi bi-plus-circle me-1"></i> Sugerir Novo Termo
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
                            <th class="text-center">Status</th>
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
        const tabela = document.getElementById("tabelaTermo");
        const contador = document.getElementById("totalTermos");

        try {
            const response = await fetch("http://localhost/2025/termo_tecnico/api/api_termo_tecnico.php");
            const resultado = await response.json();

            if (resultado.success) {
                tabela.innerHTML = ""; // Limpa a tabela antes de carregar
                contador.innerText = resultado.data.length; // Atualiza o total

                resultado.data.forEach(termo => {
                    // Sincronizando com os nomes das colunas do seu Banco de Dados
                    const id = termo.idtermos; 
                    const nome = termo.nome_termo;
                    const desc = termo.descricao_termo;
                    const statusTexto = termo.status || "Em espera";

                    // Define a cor do Badge
                    let badgeClass = "badge-espera";
                    if(statusTexto === "Aprovado") badgeClass = "badge-aprovado";
                    if(statusTexto === "Reprovado") badgeClass = "badge-reprovado";

                    // Define o ícone
                    let icone = '<i class="bi bi-clock me-1"></i>'; // Padrão Espera
                    if(statusTexto === "Aprovado") icone = '<i class="bi bi-check-circle me-1"></i>';
                    if(statusTexto === "Reprovado") icone = '<i class="bi bi-x-circle me-1"></i>';

                    tabela.innerHTML += `
                    <tr>
                        <td class="ps-3">#${id}</td>
                        <td><strong>${nome}</strong></td>
                        <td>${desc}</td>
                        <td class="text-center">
                            <span class="badge ${badgeClass} p-2 shadow-sm">
                                ${icone} ${statusTexto}
                            </span>
                        </td>
                    </tr>`;
                });
            }
        } catch (erro) {
            console.error("Erro ao carregar termos:", erro);
            tabela.innerHTML = "<tr><td colspan='4' class='text-center text-danger'>Erro ao conectar com a API.</td></tr>";
        }
    }

    // Chama a função ao abrir a página
    carregarTermos();
</script>
</body>
</html>