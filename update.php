<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Detalhes do Termo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<main class="container py-5">
    <div class="mb-4 d-flex justify-content-between">
        <a href="config_termo_tecnico.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
        <button class="btn btn-danger" onclick="deletarTermo()">
            <i class="bi bi-trash"></i> Excluir Termo
        </button>
    </div>  
       
    <section class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header text-white px-4 py-3" style="background: linear-gradient(45deg, #1a237e, #283593);">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill me-2"></i> Detalhes do termo_tecnico
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form id="formEditarTermo">
                        <input type="hidden" id="id_termo_tecnico">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome do Termo</label>
                            <input type="text" id="nome" class="form-control form-control-lg" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Descrição Técnica</label>
                            <textarea id="descricao" class="form-control" rows="5" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tipo/Categoria</label>
                                <select id="tipo" class="form-select">
                                    <option value="Português">Português</option>
                                    <option value="Matemática">Matemática</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sala (Origem)</label>
                                <input type="text" id="nome_sala" class="form-control" readonly disabled>
                                <small class="text-muted">A sala vinculada a este registro específico.</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button type="button" onclick="salvarAlteracoes()" class="btn btn-lg w-100 text-white shadow" 
                                style="background: linear-gradient(45deg, #1a237e, #283593);">
                            <i class="bi bi-save me-2"></i> Salvar Alterações
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const termoId = urlParams.get('id');

    document.addEventListener('DOMContentLoaded', () => {
        if (!termoId) {
            alert("ID do termo não encontrado.");
            window.location.href = 'config_termo_tecnico.php';
            return;
        }
        carregarDados();
    });

    async function carregarDados() {
        try {
            // Reutiliza a API de listagem, mas podemos filtrar ou criar uma específica para um ID
            const res = await fetch(`api/api_termo_tecnico.php`);
            const result = await res.json();
            
            if (result.success) {
                // Encontra o termo específico no array
                const termo = result.data.find(t => t.id_termo_tecnico == termoId);
                
                if (termo) {
                    document.getElementById('id_termo_tecnico').value = termo.id_termo_tecnico;
                    document.getElementById('nome').value = termo.nome;
                    document.getElementById('descricao').value = termo.descricao_termo;
                    document.getElementById('tipo').value = termo.tipo_termo;
                    document.getElementById('nome_sala').value = termo.nome_sala || 'Nenhuma sala';
                }
            }
        } catch (error) {
            console.error("Erro ao carregar detalhes:", error);
        }
    }

    async function salvarAlteracoes() {
        const dados = {
            id_termo_tecnico: document.getElementById('id_termo_tecnico').value,
            nome: document.getElementById('nome').value,
            descricao: document.getElementById('descricao').value,
            tipo: document.getElementById('tipo').value
        };

        try {
            const res = await fetch('api/api_termo_tecnico.php', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dados)
            });
            const result = await res.json();
            alert(result.message);
            if(result.success) window.location.reload();
        } catch (error) {
            alert("Erro ao salvar.");
        }
    }

    async function deletarTermo() {
        if (confirm("Tem certeza que deseja excluir este termo permanentemente?")) {
            try {
                const res = await fetch('api/api_termo_tecnico.php', {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id_termo_tecnico: termoId })
                });
                const result = await res.json();
                alert(result.message);
                if(result.success) window.location.href = 'config_termo_tecnico.php';
            } catch (error) {
                alert("Erro ao excluir.");
            }
        }
    }
</script>
</body>
</html>