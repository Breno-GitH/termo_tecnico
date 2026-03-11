<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<main class="container-fluid py-4">
    <div class="mb-4">
        <a href="config_termo_tecnico.php" class="btn btn-outline-secondary">Voltar</a>
    </div>  
       
     <section class="col-lg-8 col-md-7">
            <div class="card shadow-sm h-100 ">
                <div class="card-header text-white px-4 py-3" style="background: linear-gradient(45deg, #1a237e, #283593); border-bottom: 2px solid #283593;">
    <h5 class="mb-0 d-flex align-items-center">
        <i class="bi bi-person-badge-fill me-2"></i> Editar termo_tecnico
    </h5>
</div>
     <div class="card-body">
                    <form id="formAtribuir"> 
                        <div class="mb-3">
                            <label for="select_termo_tecnico" class="form-label">termo_tecnico</label>
                            <select class="form-select" id="select_termo_tecnico" required>
                                <option value="">Carregando termo_tecnico...</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="prioridade" class="form-label">Novo nome</label>
                                 <input type="form" name="id_Novo-termo_tecnico" class="form-select">
</div>
   <hr>
                        <button type="submit" class="btn btn-lg w-100 py-3 mt-3 text-white shadow border-0 rounded-3 d-flex align-items-center justify-content-center" 
        id="btnConfirmar" 
        style="background: linear-gradient(45deg, #1a237e, #283593); transition: transform 0.2s, background 0.3s;">
    <i class="bi bi-check-circle-fill me-2"></i> 
    <strong>Confirmar Atribuição</strong>
</button>
                    </form>
                </div>
            </div>
      
        </section>
    </div>
</main>