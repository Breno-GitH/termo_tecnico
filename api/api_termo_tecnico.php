<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// CONFIGURAÇÃO DO BANCO (Ajuste se sua senha for diferente)
$host = "localhost";
$db_name = "termo_tecnico_db";
$username = "root";
$password = ""; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erro de conexão: " . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

// OPERAÇÃO DE CREATE (POST)
if ($method == 'POST') {
    if (!empty($data->nome) && !empty($data->descricao)) {
        try {
            // No seu diagrama a tabela é 'termos'
            $sql = "INSERT INTO termos (nome_termo, descricao_termo, tipo_termo) VALUES (:nome, :desc, :tipo)";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':nome', $data->nome);
            $stmt->bindParam(':desc', $data->descricao);
            $stmt->bindParam(':tipo', $data->tipo);
            
            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Termo criado com sucesso!"]);
            }
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "message" => "Erro ao inserir: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Dados incompletos."]);
    }
}

// OPERAÇÃO DE LISTAR (GET) - Para o Dashboard
if ($method == 'GET') {
    $stmt = $conn->prepare("SELECT idtermos as id_termo_tecnico, nome_termo as nome, descricao_termo, tipo_termo FROM termos");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["success" => true, "data" => $result]);
}
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