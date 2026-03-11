<?php
session_start();
require_once '../config/database.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Acesso negado. Efetue login."]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$user_id_logado = $_SESSION['user_id'];

switch ($method) {
    case 'GET':
        $sql = "SELECT t.idtermos AS id_termo_tecnico, 
                       t.nome_termo AS nome, 
                       t.descricao_termo, 
                       t.tipo_termo, 
                       s.nome_sala
                FROM termos t
                LEFT JOIN salas s ON t.salas_idsalas = s.idsalas
                ORDER BY t.nome_termo ASC";

        $result = $conn->query($sql);
        $termos = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $termos[] = $row;
            }
        }
        echo json_encode(["success" => true, "data" => $termos]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        if (!isset($data->nome) || !isset($data->descricao) || !isset($data->tipo) || !isset($data->id_sala)) {
            echo json_encode(["success" => false, "message" => "Dados incompletos para criar o termo_tecnico."]);
            exit;
        }

        $nome = $conn->real_escape_string(trim($data->nome));
        $descricao = $conn->real_escape_string(trim($data->descricao));
        $tipo = $conn->real_escape_string($data->tipo); 
        $id_sala = (int)$data->id_sala;

        $sql = "INSERT INTO termos (nome_termo, descricao_termo, tipo_termo, salas_idsalas, usuario_idusuario) 
                VALUES ('$nome', '$descricao', '$tipo', $id_sala, $user_id_logado)";

        if ($conn->query($sql) === TRUE) {
            echo json_encode([
                "success" => true, 
                "message" => "termo_tecnico criado com sucesso!", 
                "id_termo_tecnico" => $conn->insert_id
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Erro ao criar termo_tecnico: " . $conn->error]);
        }
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