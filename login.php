<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login - Dicionário Técnico</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

:root{
--magenta:#F500C0;
--ciano:#4EE5F0;
--amarelo:#E5D84E;
}

body{
height:100vh;
display:flex;
align-items:center;
justify-content:center;
background:#f5f5f5;
font-family:Arial, Helvetica, sans-serif;
}

.login-card{
width:350px;
border-radius:12px;
box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

.btn-login{
background:var(--magenta);
color:white;
border:none;
}

.btn-login:hover{
background:#c4009b;
color:white;
}

</style>

</head>

<body>

<div class="card login-card p-4">

<h4 class="text-center mb-4">Dicionário Técnico</h4>

<form action="api/login.php" method="POST">

<div class="mb-3">
<label>Nome</label>
<input type="text" name="nome" class="form-control">
</div>

<div class="mb-3">
<label>Senha</label>
<input type="password" name="senha" class="form-control">
</div>

<div class="mb-3">
<label>Perfil</label>
<select name="perfil" class="form-select">
<option value="matemática">Matemática</option>
<option value="português">Português</option>
<option value="aluno">Aluno</option>
</select>
</div>

<button class="btn btn-login">Entrar</button>

</form>

</div>

</body>
</html>