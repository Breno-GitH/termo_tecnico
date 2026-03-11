<?php

session_start();

require_once "../config/database.php";

$nome = $_POST['nome'] ?? '';
$senha = $_POST['senha'] ?? '';
$perfil = $_POST['perfil'] ?? '';

$sql = "SELECT * FROM usuario
        WHERE nome_usuario = ?
        AND senha_usuario = ?
        AND perfil = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("sss", $nome, $senha, $perfil);

$stmt->execute();

$result = $stmt->get_result();
if($result->num_rows > 0){

    $usuario = $result->fetch_assoc();

    $_SESSION['id'] = $usuario['idusuario'];
    $_SESSION['usuario'] = $usuario['nome_usuario'];
    $_SESSION['perfil'] = $usuario['perfil'];

    header("Location: ../dashboard.php");
    exit;

}else{

    echo "Usuário, senha ou perfil incorretos";

}
?>