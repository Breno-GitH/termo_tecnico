<?php
// Proteção de Sessão
session_start();

// 1. Verifica se está logado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

// 2. Identifica o perfil salvo no login.php
// Usamos mb_strtolower para garantir que a comparação seja precisa
$perfil = isset($_SESSION['perfil']) ? mb_strtolower($_SESSION['perfil'], 'UTF-8') : 'aluno';

// 3. Define a rota baseada nos seus critérios do login.php
if ($perfil == 'português' || $perfil == 'matemática') {
    $url_dashboard = 'dashboard.php';
} else {
    // Se for 'aluno' ou qualquer outra coisa, vai para a do usuário
    $url_dashboard = 'dashboard_usuario.php';
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
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i> 
                        Criar Termo (Perfil: <?php echo ucfirst($perfil); ?>)
                    </h4>
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
                        </div>

                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-criar btn-lg py-3" onclick="executarCreate()">
                                Registrar Termo Técnico
                            </button>
                            <a href="<?php echo $url_dashboard; ?>" class="btn btn-outline-secondary">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Passamos a URL correta decidida pelo PHP para o JavaScript
    const ROTA_RETORNO = "<?php echo $url_dashboard; ?>";

    async function executarCreate() {
        const btn = document.querySelector('.btn-criar');
        const formData = new FormData();
        
        formData.append('nome', document.getElementById('nome_termo').value);
        formData.append('tipo', document.getElementById('tipo_termo').value);
        formData.append('descricao', document.getElementById('descricao_termo').value);
        
        const imagemInput = document.getElementById('imagem_termo');
        if (imagemInput.files[0]) {
            formData.append('imagem', imagemInput.files[0]);
        }

        if(!document.getElementById('nome_termo').value || !document.getElementById('descricao_termo').value) {
            alert("Preencha os campos obrigatórios.");
            return;
        }

        btn.disabled = true;
        btn.innerHTML = "Processando...";

        try {
            const response = await fetch('api/api_termo_tecnico.php', { 
                method: 'POST', 
                body: formData 
            });
            const result = await response.json();
            
            if (result.success) {
                alert("Termo registrado!");
                // Redireciona para a rota definida no início do arquivo
                window.location.href = ROTA_RETORNO;
            } else {
                alert("Erro: " + result.message);
                btn.disabled = false;
                btn.innerHTML = "Registrar Termo Técnico";
            }
        } catch (error) {
            alert("Erro na comunicação com o servidor.");
            btn.disabled = false;
            btn.innerHTML = "Registrar Termo Técnico";
        }
    }
</script>
</body>
</html>