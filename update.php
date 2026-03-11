<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Update Termo Técnico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --magenta: #F500C0; }
        body { background: #f4f6f7; }
        .card-header { 
            background: var(--magenta); 
            color: white; 
            font-weight: bold; 
            border-radius: 15px 15px 0 0 !important; 
        }
        .btn-update { 
            background: var(--magenta); 
            color: white; 
            border: none; 
            transition: 0.3s;
        }
        .btn-update:hover { 
            background: #c4009b; 
            color: white; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 0, 192, 0.3);
        }
        .form-label { color: #444; }
    </style>
</head>
<body>

<main class="container py-5">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="config_termo_tecnico.php" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left"></i> Voltar ao Dashboard
        </a>
        <button class="btn btn-danger shadow-sm" onclick="deletarTermo()">
            <i class="bi bi-trash"></i> Excluir Registro
        </button>
    </div>  
       
    <section class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header p-4">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-arrow-repeat me-2"></i> Update de Termo Técnico
                    </h5>
                </div>
                
                <div class="card-body p-4 bg-white" style="border-radius: 0 0 15px 15px;">
                    <form id="formUpdateTermo">
                        <input type="hidden" id="id_termo_tecnico">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome do Termo</label>
                            <input type="text" id="nome" class="form-control form-control-lg border-2" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descrição Técnica</label>
                            <textarea id="descricao" class="form-control border-2" rows="6" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Área (Categoria)</label>
                                <select id="tipo" class="form-select border-2">
                                    <option value="Português">Português</option>
                                    <option value="Matemática">Matemática</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ID da Sala</label>
                                <input type="text" id="id_sala_display" class="form-control bg-light" readonly disabled>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button type="button" onclick="executarUpdate()" class="btn btn-lg w-100 btn-update shadow py-3">
                            <i class="bi bi-save2 me-2"></i> Salvar Alterações (Update)
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    // Pega o ID da URL (?id=X)
    const urlParams = new URLSearchParams(window.location.search);
    const termoId = urlParams.get('id');

    document.addEventListener('DOMContentLoaded', () => {
        if (!termoId) {
            alert("Erro: ID do termo não foi enviado.");
            window.location.href = 'config_termo_tecnico.php';
            return;
        }
        carregarDadosParaUpdate();
    });

    // Busca os dados atuais do banco termo_tecnico_db
    async function carregarDadosParaUpdate() {
        try {
            const res = await fetch(`api/api_termo_tecnico.php`);
            const result = await res.json();
            
            if (result.success) {
                // Filtra o termo específico pelo ID
                const termo = result.data.find(t => t.id_termo_tecnico == termoId);
                
                if (termo) {
                    document.getElementById('id_termo_tecnico').value = termo.id_termo_tecnico;
                    document.getElementById('nome').value = termo.nome;
                    document.getElementById('descricao').value = termo.descricao_termo;
                    document.getElementById('tipo').value = termo.tipo_termo;
                    document.getElementById('id_sala_display').value = termo.salas_idsalas;
                }
            }
        } catch (error) {
            console.error("Erro ao carregar dados:", error);
        }
    }

    // Função que envia o PUT (Update) para a API
    async function executarUpdate() {
        const btn = document.querySelector('.btn-update');
        const dados = {
            id_termo_tecnico: document.getElementById('id_termo_tecnico').value,
            nome: document.getElementById('nome').value,
            descricao: document.getElementById('descricao').value,
            tipo: document.getElementById('tipo').value
        };

        btn.disabled = true;
        btn.innerHTML = "<span class='spinner-border spinner-border-sm'></span> Processando Update...";

        try {
            const res = await fetch('api/api_termo_tecnico.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            });
            
            const result = await res.json();
            
            if(result.success) {
                alert("Update realizado com sucesso!");
                window.location.href = 'config_termo_tecnico.php';
            } else {
                alert("Erro no Update: " + result.message);
                btn.disabled = false;
                btn.innerHTML = "<i class='bi bi-save2 me-2'></i> Salvar Alterações (Update)";
            }
        } catch (error) {
            alert("Erro na comunicação com o servidor.");
            btn.disabled = false;
            btn.innerHTML = "<i class='bi bi-save2 me-2'></i> Salvar Alterações (Update)";
        }
    }

    // Função de Exclusão
    async function deletarTermo() {
        if (confirm("Deseja realmente excluir este registro?")) {
            try {
                const res = await fetch('api/api_termo_tecnico.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_termo_tecnico: termoId })
                });
                const result = await res.json();
                if(result.success) window.location.href = 'dashboard.php';
            } catch (error) {
                alert("Erro ao excluir.");
            }
        }
    }
</script>
</body>
</html>