<?php
session_start();
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

switch ($method) {
    case 'POST':
        $nome = $_POST['nome'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
        $usuario_id = $_SESSION['id'] ?? 1;
        $sala_id = 2;

        if (!empty($nome) && !empty($descricao)) {
            try {
                $nomeImagem = null;
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                    $diretorio = "uploads/";
                    if (!is_dir($diretorio)) mkdir($diretorio, 0777, true);
                    $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                    $nomeImagem = uniqid() . "." . $extensao;
                    move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorio . $nomeImagem);
                }

                $sql = "INSERT INTO termos (nome_termo, descricao_termo, tipo_termo, imagem_termo, status, salas_idsalas, usuario_idusuario) 
                        VALUES (:nome, :desc, :tipo, :img, :status, :sala, :usuario)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    ':nome'    => $nome,
                    ':desc'    => $descricao,
                    ':tipo'    => $tipo,
                    ':img'     => $nomeImagem,
                    ':status'  => 'Em espera',
                    ':sala'    => $sala_id,
                    ':usuario' => $usuario_id  
                ]);
                echo json_encode(["success" => true, "message" => "Termo criado!"]);
            } catch(PDOException $e) { echo json_encode(["success" => false, "message" => $e->getMessage()]); }
        }
        break;

    case 'GET':
        $sql = "SELECT idtermos, nome_termo, descricao_termo, tipo_termo, imagem_termo, status, salas_idsalas, usuario_idusuario FROM termos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        echo json_encode(["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        try {
            // Ajustado para aceitar idtermos ou id_termo_tecnico
            $id = $data->idtermos ?? $data->id_termo_tecnico;
            $sql = "UPDATE termos SET nome_termo = :nome, descricao_termo = :desc, tipo_termo = :tipo, status = :status WHERE idtermos = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':nome'   => $data->nome_termo ?? $data->nome,
                ':desc'   => $data->descricao_termo ?? $data->descricao,
                ':tipo'   => $data->tipo_termo ?? $data->tipo,
                ':status' => $data->status ?? 'Em espera',
                ':id'     => $id
            ]);
            echo json_encode(["success" => true]);
        } catch(PDOException $e) { echo json_encode(["success" => false, "message" => $e->getMessage()]); }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        try {
            // Tenta pegar o ID de qualquer uma das variações enviadas pelo JS
            $id = $data->idtermos ?? $data->id_termo_tecnico;
            
            if (!$id) {
                echo json_encode(["success" => false, "message" => "ID não fornecido"]);
                break;
            }

            $stmt = $conn->prepare("DELETE FROM termos WHERE idtermos = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(["success" => true, "message" => "Excluído com sucesso"]);
        } catch(PDOException $e) { echo json_encode(["success" => false, "message" => $e->getMessage()]); }
        break;
}
?>