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

        /* Badges de Status */
        .badge-espera { background-color: #ffc107; color: #000; }
        .badge-aprovado { background-color: #198754; color: #fff; }
        .badge-reprovado { background-color: #dc3545; color: #fff; }
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
            <h4>Gestão de Termos (Avaliação)</h4>
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
    const API_URL = "http://localhost/2025/termo_tecnico/api/api_termo_tecnico.php";

    async function carregarTermos() {
        const tabela = document.getElementById("tabelaTermo");
        try {
            const response = await fetch(API_URL);
            const resultado = await response.json();

            if (resultado.success) {
                tabela.innerHTML = "";
                document.getElementById("totalTermos").innerText = resultado.data.length;

                resultado.data.forEach(termo => {
                    // NOMES EXATOS DO SEU BANCO DE DADOS (Captura 090553)
                    const id = termo.idtermos; 
                    const nome = termo.nome_termo;
                    const desc = termo.descricao_termo;
                    const statusAtual = termo.status || "Em espera";
                    
                    let badgeClass = "badge-espera";
                    if(statusAtual === "Aprovado") badgeClass = "badge-aprovado";
                    if(statusAtual === "Reprovado") badgeClass = "badge-reprovado";

                    tabela.innerHTML += `
                    <tr>
                        <td class="ps-3">#${id}</td>
                        <td><strong>${nome}</strong></td>
                        <td>${desc}</td>
                        <td class="text-center"><span class="badge ${badgeClass}">${statusAtual}</span></td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <button onclick="alterarStatus(${id}, 'Aprovado')" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                <button onclick="alterarStatus(${id}, 'Reprovado')" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                <a href="update.php?id=${id}" class="btn btn-sm btn-editar"><i class="bi bi-pencil-square"></i></a>
                            </div>
                        </td>
                    </tr>`;
                });
            }
        } catch (erro) {
            console.error("Erro ao carregar:", erro);
            tabela.innerHTML = "<tr><td colspan='5' class='text-center text-danger'>Erro ao ler dados da API. Verifique o Console (F12).</td></tr>";
        }
    }

  async function alterarStatus(id, novoStatus) {
    try {
        // 1. Busca a lista atual para pegar os dados do termo
        const responseBusca = await fetch(API_URL);
        const res = await responseBusca.json();
        const termo = res.data.find(t => t.idtermos == id);

        if (!termo) return;

        // 2. Monta o objeto com os nomes de colunas que o PHP/Banco esperam
        const dadosUpdate = {
            idtermos: id,
            nome_termo: termo.nome_termo,
            descricao_termo: termo.descricao_termo,
            tipo_termo: termo.tipo_termo,
            status: novoStatus // O novo status (Aprovado ou Reprovado)
        };

        const response = await fetch(API_URL, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(dadosUpdate)
        });

        const final = await response.json();
        if (final.success) {
            alert("Status alterado para: " + novoStatus);
            carregarTermos(); // Recarrega a tabela
        } else {
            alert("Erro ao salvar: " + final.message);
        }
    } catch (error) {
        console.error("Erro na conexão:", error);
    }
}

    carregarTermos();
</script>
</body>
</html>