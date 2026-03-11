<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>SGM - Novo termo_tecnico</title>
</head>

<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh; padding: 20px 0;">
        <div class="card w-100 shadow-lg" style="max-width: 600px; border-radius: 15px; overflow: hidden;">
            
            <header class="text-white d-flex justify-content-between align-items-center px-4 py-3" 
                    style="background: linear-gradient(45deg,#F500C0);">
                <h2 class="mb-0 h4 d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill me-2"></i> Criar termo_tecnico
                </h2>
                <a href="dashboard.php" class="btn btn-sm btn-outline-light">Voltar</a>
            </header>

            <main class="p-4 bg-white">
                <form id="formTermo">
                    <div class="mb-3">
                        <label for="nome" class="form-label fw-bold">Nome do Termo</label>
                        <input type="text" id="nome" class="form-control" placeholder="Ex: Fotossíntese" required>
                    </div>

                    <div class="mb-3">
                        <label for="descricao" class="form-label fw-bold">Descrição Técnica</label>
                        <textarea id="descricao" class="form-control" rows="4" placeholder="Descreva o termo detalhadamente..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="tipo" class="form-label fw-bold">Área do Conhecimento</label>
                        <select id="tipo" class="form-select" required>
                            <option value="Português">Português</option>
                            <option value="Matemática">Matemática</option>
                        </select>

                    <button class="btn btn-lg w-100 py-3 text-white shadow-sm border-0 rounded-3" 
                            type="button" onclick="enviar_termo_tecnico()" id="btnEnviar" 
                            style="background: linear-gradient(45deg, #F500C0);">
                        <i class="bi bi-check2-all me-2"></i> Registrar termo tecnico
                    </button>   
                </form>
            </main>
        </div>
    </div>

    <script>
        async function enviar_termo_tecnico() {
            const btn = document.getElementById('btnEnviar');
            const dados = {
                nome: document.getElementById('nome').value,
                descricao: document.getElementById('descricao').value,
                tipo: document.getElementById('tipo').value
            };

            if (!dados.nome || !dados.descricao) {
                alert("Por favor, preencha todos os campos.");
                return;
            }

            btn.disabled = true;
            btn.innerHTML = "Processando...";

            try {
                const response = await fetch('api/api_termo_tecnico.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(dados)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    window.location.href = 'config_termo_tecnico.php';
                } else {
                    alert('Erro: ' + result.message);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-check2-all me-2"></i> Registrar termo_tecnico';
                }
            } catch (error) {
                alert('Erro na comunicação com o servidor.');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check2-all me-2"></i> Registrar termo_tecnico';
            }
        }
    </script>
</body>
</html>