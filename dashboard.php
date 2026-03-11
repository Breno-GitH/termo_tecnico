<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dicionário Técnico - Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

:root{
--magenta:#F500C0;
--ciano:#4EE5F0;
--amarelo:#E5D84E;
--cinza:#607575;
--roxo:#7B3D6E;
}

body{
background:#f4f6f7;
}

/* NAVBAR */

.navbar{
background:var(--magenta);
}

.navbar-brand{
color:white;
font-weight:bold;
}

/* CARDS */

.card-dashboard{
border:none;
border-radius:12px;
color:white;
padding:10px;
}

.card-magenta{
background:var(--magenta);
}

.card-ciano{
background:var(--ciano);
color:black;
}

.card-amarelo{
background:var(--amarelo);
color:black;
}

/* BOTÃO */

.btn-criar{
background:var(--magenta);
color:white;
border:none;
}

.btn-criar:hover{
background:#c4009b;
}

/* TABELA */

.table thead{
background:var(--cinza);
color:white;
}

</style>

</head>

<body>

<!-- NAVBAR -->

<nav class="navbar navbar-expand-lg">
<div class="container">
<a class="navbar-brand">Dicionário Técnico</a>
</div>
</nav>

<div class="container mt-4">

<!-- CARDS -->

<div class="row g-3 mb-4">

<div class="col-md-4">
<div class="card-dashboard card-magenta text-center">
<h5>Total de Termos</h5>
<h2>120</h2>
</div>
</div>

</div>

<!-- BOTÃO -->

<div class="d-flex justify-content-between mb-3">

<h4>Lista de Termos</h4>

<a href="create.php"><button class="btn btn-criar">Criar Termo Técnico</button>
</a>
</div>

<!-- TABELA -->

<div class="card shadow-sm">

<div class="card-body">

<table class="table table-hover">

<thead>
<tr>
<th>ID</th>
<th>Nome do Termo</th>
<th>Descrição</th>
<th>Ações</th>
</tr>
</thead>

<tbody id="tabelaTermo">
</tbody>

</table>

</div>

</div>

</div>


<!-- MODAL CRIAR TERMO -->

<div class="modal fade" id="modalTermo">

<div class="modal-dialog">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">Novo Termo Técnico</h5>

<button class="btn-close" data-bs-dismiss="modal"></button>

</div>

<div class="modal-body">

<form>

<div class="mb-3">
<label class="form-label">Termo</label>
<input type="text" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Categoria</label>
<input type="text" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Descrição</label>
<textarea class="form-control"></textarea>
</div>

</form>

</div>

<div class="modal-footer">

<button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

<button class="btn btn-criar">Salvar</button>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

async function carregarTermos() {

    try {

        const response = await fetch("http://localhost/2025/termo_tecnico/api/api_termo_tecnico.php");
        const resultado = await response.json();

        if(resultado.success){

            const tabela = document.getElementById("tabelaTermo");
            tabela.innerHTML = "";

            resultado.data.forEach(termo => {

                tabela.innerHTML += `
                <tr>
                    <td>${termo.id_termo_tecnico}</td>
                    <td>${termo.nome}</td>
                    <td>${termo.descricao_termo}</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Editar</button>
                        <button class="btn btn-sm btn-danger">Excluir</button>
                    </td>
                </tr>
                `;

            });

        }

    } catch (erro) {
        console.error("Erro ao carregar termos:", erro);
    }

}

carregarTermos();

</script>
</body>
</html>