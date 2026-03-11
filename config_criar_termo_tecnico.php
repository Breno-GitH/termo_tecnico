<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>SGM - Novo termo_tecnico</title>
</head>

<body class="bg-light">
    <div class="container d-flex align-items-center" style="min-height: 100vh; padding: 20px 0;">
        <form action="api/salvar_mudancas.php" method="POST" enctype="multipart/form-data" class="w-100 m-auto shadow-lg" id="formChamado" style="max-width: 600px;">
            <header class="text-white d-flex justify-content-between align-items-center rounded-top-4 px-4 py-3" style="background: linear-gradient(45deg, #1a237e, #283593);">
                <h2 class="mb-0 h4 d-flex align-items-center">
                    <i class="bi bi-plus-circle-fill me-2"></i> Criar termo_tecnico
                </h2>
                 <a href="config_termo_tecnico.php" class="btn btn-sm btn-outline-light">Voltar</a>
            </header>

            <main class="p-4 border rounded-bottom bg-white">
                <div class="mb-3">
                    <label for="nome_termo_tecnico" class="form-label fw-bold">Nome do termo_tecnico</label>
                    <input type="text" id="nome_termo_tecnico" class="form-control" placeholder="Ex: Laboratório 01" required>
                    
                <button class="btn btn-lg w-100 py-3 mt-3 text-white shadow-sm border-0 rounded-3" 
                        type="button" onclick="enviar_termo_tecnico()" id="btnEnviar" 
                        style="background: linear-gradient(45deg, #1a237e, #283593);">
                    <i class="bi bi-check2-all me-2"></i> Registrar termo_tecnico
                </button>   
            </main>
        </form>
    </div>
    <script>
            
        
        async function criar_termo_tecnico(id) {
            if (confirm('Tem certeza que deseja criar este termo_tecnico?')) {
                try {
                    const response = await fetch('api/api_termo_tecnico.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id_termo_tecnico: id })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        alert(result.message);
                        carregar_termo_tecnico();
                    } else {
                        alert('Erro: ' + result.message);
                    }
                } catch (error) {
                    alert('Erro na comunicação com o servidor.');
                }
            }
        }

        async function carregar_termo_tecnico(id_bloco) {
            const selA = document.getElementById('sala');
            
            if (!id_bloco) {
                selA.innerHTML = '<option value="">Selecione o Bloco primeiro...</option>';
                selA.disabled = true;
                return;
            }

            try {
                selA.disabled = true;
                selA.innerHTML = '<option value="">Carregando...</option>';
                
                const res = await fetch(`api/localizacoes.php?acao=listar_termo_tecnico&id_bloco=${id_bloco}`);
                const termo_tecnicos = await res.json();
                
                preencherSelect('sala', termo_tecnicos, 'id_termo_tecnico', 'nome', 'Selecione a Sala...');
                selA.disabled = false;
            } catch (erro) {
                console.error("Erro ao carregar termo_tecnicos:", erro);
                selA.innerHTML = '<option value="">Erro ao carregar</option>';
            }
        }
    </script>
</body>
</html>