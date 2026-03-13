<?php
session_start();

// Verifica se o usuário está logado
if(!isset($_SESSION['id'])){
    header('Location: login.php');
    exit;
}

$perfil = $_SESSION['perfil'];

switch($perfil){
    case 'português':
    case 'matemática':
        // Se já estiver na dashboard.php, não precisa redirecionar de novo (evita loop)
        if (basename($_SERVER['PHP_SELF']) !== 'dashboard.php') {
            header('Location: dashboard.php');
            exit;
        }
        break;
    case 'aluno':
        if (basename($_SERVER['PHP_SELF']) !== 'dashboard_usuario.php') {
            header('Location: dashboard_usuario.php');
            exit;
        }
        break;
    default:
        session_destroy();
        header('Location: login.php');
        exit;
}
?>