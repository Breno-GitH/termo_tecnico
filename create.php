<?php
// Proteção de Sessão: Se não estiver logado, volta para o login
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGM - Criar Termo Técnico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --magenta: #F500C0; }
        body { background: #f4f6f7; }
        .card-header { background: var(--magenta); color: white; font-weight: bold; border-radius: 15px 15px 0 0 !important; }
        .btn-criar { background: var(--magenta); color: white; border: none; }
        .btn-criar:hover { background: #c4009b; color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header p-4 text-center">
                    <h4 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Criar Termo Técnico</h4>
                </div>
                <div class="card-body p-4 bg-white" style="border-radius: 0 0 15px 15px;">
                    <form id="formCriarTermo">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome do Termo</label>
                            <input type="text" id="nome_termo" class="form-control" placeholder="Ex: Fotossíntese" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Área do Conhecimento</label>
                            <select id="tipo_termo" class="form-select">
                                <option value="Português">Português</option>
                                <option value="Matemática">Matemática</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Descrição Técnica</label>
                            <textarea id="descricao_termo" class="form-control" rows="6" placeholder="Definição do termo..." required></textarea>
                        </div>
                    <div class="mb-3">
    <label class="form-label fw-bold">Imagem Ilustrativa</label>
    <input type="file" id="imagem_termo" class="form-control" accept="image/*">
    <small class="text-muted">Formatos aceitos: JPG, PNG ou GIF.</small>
</div>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-criar btn-lg py-3" onclick="executarCreate()">
                                Registrar Termo Técnico
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    async function executarCreate() {
        const btn = document.querySelector('.btn-criar');
        const imagemInput = document.getElementById('imagem_termo');
        
        // Usamos FormData para enviar arquivos
        const formData = new FormData();
        formData.append('nome', document.getElementById('nome_termo').value);
        formData.append('tipo', document.getElementById('tipo_termo').value);
        formData.append('descricao', document.getElementById('descricao_termo').value);
        
        if (imagemInput.files[0]) {
            formData.append('imagem', imagemInput.files[0]);
        }

        if(!document.getElementById('nome_termo').value || !document.getElementById('descricao_termo').value) {
            alert("Preencha todos os campos!");
            return;
        }

        btn.disabled = true;
        btn.innerHTML = "Processando...";

        try {
            const response = await fetch('api/api_termo_tecnico.php', { 
                method: 'POST',
                // Removido o Content-Type: application/json, o navegador define automaticamente para FormData
                body: formData 
            });

            const result = await response.json();
            
            if (result.success) {
                alert("Sucesso! Termo criado.");
                window.location.href = 'dashboard.php';
            } else {
                alert("Erro: " + result.message);
                btn.disabled = false;
                btn.innerHTML = "Registrar Termo Técnico";
            }
        } catch (error) {
            alert("Erro na comunicação com o servidor.");
            console.error(error);
            btn.disabled = false;
        }
    }
</script>
</body>
</html>