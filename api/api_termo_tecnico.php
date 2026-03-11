<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$user_id_logado = $_SESSION['user_id'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!isset($data->nome) || !isset($data->descricao) || !isset($data->tipo)) {
            echo json_encode(["success" => false, "message" => "Dados incompletos."]);
            exit;
        }

        $nome = $conn->real_escape_string($data->nome);
        $desc = $conn->real_escape_string($data->descricao);
        $tipo = $conn->real_escape_string($data->tipo);

        // 1. Buscar todas as salas existentes
        $resultSalas = $conn->query("SELECT idsalas FROM salas");
        
        if ($resultSalas->num_rows === 0) {
            echo json_encode(["success" => false, "message" => "Nenhuma sala cadastrada. Crie uma sala primeiro."]);
            exit;
        }

        // 2. Iniciar transação para garantir que ou grava em todas ou em nenhuma
        $conn->begin_transaction();

        try {
            while ($sala = $resultSalas->fetch_assoc()) {
                $id_sala = $sala['idsalas'];
                $sql = "INSERT INTO termos (nome_termo, descricao_termo, tipo_termo, salas_idsalas, usuario_idusuario) 
                        VALUES ('$nome', '$desc', '$tipo', $id_sala, $user_id_logado)";
                $conn->query($sql);
            }
            $conn->commit();
            echo json_encode(["success" => true, "message" => "Termo adicionado em todas as salas com sucesso!"]);
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => "Erro ao replicar termo: " . $conn->error]);
        }
        break;

    case 'GET':
        // No GET, usamos DISTINCT para não mostrar o mesmo termo repetido várias vezes no dashboard
        $sql = "SELECT DISTINCT nome_termo AS nome, descricao_termo, tipo_termo 
                FROM termos ORDER BY nome_termo ASC";
        $result = $conn->query($sql);
        $termos = [];
        while ($row = $result->fetch_assoc()) { $termos[] = $row; }
        echo json_encode(["success" => true, "data" => $termos]);
        break;
        

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id_termo_tecnico) || !isset($data->nome) || !isset($data->descricao)) {
            echo json_encode(["success" => false, "message" => "Dados incompletos para atualização."]);
            exit;
        }

        $id = (int)$data->id_termo_tecnico;
        $nome = $conn->real_escape_string(trim($data->nome));
        $descricao = $conn->real_escape_string(trim($data->descricao));
        $tipo = $conn->real_escape_string($data->tipo);
        $id_sala = (int)$data->id_sala;

        $sql = "UPDATE termos SET 
                nome_termo = '$nome', 
                descricao_termo = '$descricao', 
                tipo_termo = '$tipo', 
                salas_idsalas = $id_sala 
                WHERE idtermos = $id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "termo_tecnico atualizado com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao atualizar termo_tecnico: " . $conn->error]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!isset($data->id_termo_tecnico)) {
            echo json_encode(["success" => false, "message" => "ID não fornecido."]);
            exit;
        }

        $id = (int)$data->id_termo_tecnico;
        $sql = "DELETE FROM termos WHERE idtermos = $id";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "termo_tecnico excluído com sucesso!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao excluir: " . $conn->error]);
        }
        break;

    default:
        echo json_encode(["success" => false, "message" => "Método não suportado."]);
        break;
}
?>