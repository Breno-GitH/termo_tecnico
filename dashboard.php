<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Técnico - Professor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root { --magenta: #F500C0; --amarelo: #E5D84E; --cinza: #607575; }
        body { background: #f4f6f7; }
        .navbar { background: var(--magenta); }
        .navbar-brand { color: white; font-weight: bold; }
        .card-dashboard { border: none; border-radius: 12px; color: white; padding: 20px; }
        .card-magenta { background: var(--magenta); }
        .btn-criar { background: var(--magenta); color: white; border: none; }
        .btn-criar:hover { background: #c4009b; color: white; }
        .table thead { background: var(--cinza); color: white; }
        .btn-editar { background-color: var(--amarelo); color: black; border: none; }
        .badge-espera { background-color: #ffc107; color: #000; }
        .badge-aprovado { background-color: #198754; color: #fff; }
        .badge-reprovado { background-color: #dc3545; color: #fff; }
        .img-termo-admin { width: 40px; height: 40px; object-fit: cover; border-radius: 6px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="bi bi-book me-2"></i>Dicionário Técnico - Professor</a>
            <div class="d-flex align-items-center">
                <a href="api/logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i> Sair</a>
            </div>
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
            <a href="create.php" class="btn btn-criar"><i class="bi bi-plus-circle me-1"></i> Criar Termo Técnico</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Imagem</th>
                            <th>Nome do Termo</th>
                            <th>Descrição</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaTermo"></tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const API_URL = "api/api_termo_tecnico.php";

        async function carregarTermos() {
            const tabela = document.getElementById("tabelaTermo");
            try {
                const response = await fetch(API_URL);
                const resultado = await response.json();

                if (resultado.success) {
                    tabela.innerHTML = "";
                    document.getElementById("totalTermos").innerText = resultado.data.length;

                    resultado.data.forEach(termo => {
                        const imgPath = termo.imagem_termo ? `api/uploads/${termo.imagem_termo}` : 'https://via.placeholder.com/40';
                        let badgeClass = "badge-espera";
                        if(termo.status === "Aprovado") badgeClass = "badge-aprovado";
                        if(termo.status === "Reprovado") badgeClass = "badge-reprovado";

                        tabela.innerHTML += `
                        <tr>
                            <td class="ps-3">#${termo.idtermos}</td>
                            <td><img src="${imgPath}" class="img-termo-admin shadow-sm"></td>
                            <td><strong>${termo.nome_termo}</strong></td>
                            <td>${termo.descricao_termo}</td>
                            <td class="text-center"><span class="badge ${badgeClass}">${termo.status || "Em espera"}</span></td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <button onclick="alterarStatus(${termo.idtermos}, 'Aprovado')" class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i></button>
                                    <button onclick="alterarStatus(${termo.idtermos}, 'Reprovado')" class="btn btn-sm btn-danger"><i class="bi bi-x-lg"></i></button>
                                    <a href="update.php?id=${termo.idtermos}" class="btn btn-sm btn-editar"><i class="bi bi-pencil-square"></i></a>
                                </div>
                            </td>
                        </tr>`;
                    });
                }
            } catch (erro) {
                console.error(erro);
            }
        }

        async function alterarStatus(id, novoStatus) {
            try {
                const responseBusca = await fetch(API_URL);
                const res = await responseBusca.json();
                const termo = res.data.find(t => t.idtermos == id);

                const response = await fetch(API_URL, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ...termo, status: novoStatus })
                });

                if ((await response.json()).success) {
                    alert("Status atualizado!");
                    carregarTermos();
                }
            } catch (error) { console.error(error); }
        }

        carregarTermos();
    </script>
</body>
</html>