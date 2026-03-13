<?php
session_start();
require_once "../config/database.php";

$nome = $_POST['nome'] ?? '';
$senha = $_POST['senha'] ?? '';
$perfil_form = $_POST['perfil'] ?? ''; // Perfil que veio do formulário

$sql = "SELECT * FROM usuario 
        WHERE nome_usuario = ? 
        AND senha_usuario = ? 
        AND perfil = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $nome, $senha, $perfil_form);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    $usuario = $result->fetch_assoc();

    $_SESSION['id'] = $usuario['idusuario'];
    $_SESSION['usuario'] = $usuario['nome_usuario'];
    $_SESSION['perfil'] = $usuario['perfil'];

    // Forçamos o valor para minúsculo para evitar erro de Digitação/Banco
    $perfil_banco = mb_strtolower($usuario['perfil'], 'UTF-8');

    if ($perfil_banco == 'português' || $perfil_banco == 'matemática') {
        header("Location: ../dashboard.php");
        exit;
    } elseif ($perfil_banco == 'aluno') {
        header("Location: ../dashboard_usuario.php");
        exit;
    } else {
        // Se cair aqui, vamos debugar:
        die("Perfil encontrado no banco: " . $perfil_banco . " (Não corresponde às regras)");
    }

} else {
    echo "Usuário, senha ou perfil incorretos.";
}
?>