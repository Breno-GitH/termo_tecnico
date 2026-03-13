<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

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

switch ($method) {
    case 'POST':
        if (!empty($data->nome) && !empty($data->descricao)) {
            try {
                $sql = "INSERT INTO termos (nome_termo, descricao_termo, tipo_termo, status, salas_idsalas, usuario_idusuario) 
                        VALUES (:nome, :desc, :tipo, :status, :sala, :usuario)";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':nome'    => $data->nome,
                    ':desc'    => $data->descricao,
                    ':tipo'    => $data->tipo,
                    ':status'  => 'Em espera',
                    ':sala'    => 2,
                    ':usuario' => 1  
                ]);
                echo json_encode(["success" => true, "message" => "Termo criado!"]);
            } catch(PDOException $e) {
                echo json_encode(["success" => false, "message" => "Erro de Banco: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Campos obrigatórios vazios."]);
        }
        break;

    case 'GET':
        // Buscando todos os campos necessários, incluindo o status
        $sql = "SELECT idtermos, nome_termo, descricao_termo, tipo_termo, status, salas_idsalas, usuario_idusuario FROM termos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        echo json_encode(["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;

    case 'PUT':
        try {
            // O UPDATE agora aceita o campo status enviado pelo professor
            $sql = "UPDATE termos SET 
                    nome_termo = :nome, 
                    descricao_termo = :desc, 
                    tipo_termo = :tipo, 
                    status = :status 
                    WHERE idtermos = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nome'   => $data->nome_termo,
                ':desc'   => $data->descricao_termo,
                ':tipo'   => $data->tipo_termo,
                ':status' => $data->status,
                ':id'     => $data->idtermos
            ]);
            echo json_encode(["success" => true]);
        } catch(PDOException $e) {
            echo json_encode(["success" => false, "message" => "Erro ao atualizar: " . $e->getMessage()]);
        }
        break;

    case 'DELETE':
        $stmt = $conn->prepare("DELETE FROM termos WHERE idtermos = :id");
        $stmt->execute([':id' => $data->idtermos]);
        echo json_encode(["success" => true]);
        break;
}
?>