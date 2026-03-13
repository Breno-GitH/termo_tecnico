<?php
// Inicia a sessão para capturar o ID do usuário logado
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
        // Quando usamos FormData no JS, os dados chegam via $_POST e $_FILES
        $nome = $_POST['nome'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $tipo = $_POST['tipo'] ?? null;
        $usuario_id = $_SESSION['id'] ?? 1; // Pega o ID da sessão ou usa 1 como padrão
        $sala_id = 2; // Valor padrão conforme seu código original

        if (!empty($nome) && !empty($descricao)) {
            try {
                // Lógica de Upload da Imagem
                $nomeImagem = null;
                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
                    $diretorio = "uploads/";
                    
                    // Cria a pasta se não existir
                    if (!is_dir($diretorio)) {
                        mkdir($diretorio, 0777, true);
                    }

                    $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
                    $nomeImagem = uniqid() . "." . $extensao; // Gera nome único
                    
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
                
                echo json_encode(["success" => true, "message" => "Termo criado com sucesso!"]);
            } catch(PDOException $e) {
                echo json_encode(["success" => false, "message" => "Erro de Banco: " . $e->getMessage()]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Campos obrigatórios vazios."]);
        }
        break;

    case 'GET':
        // Seleciona todos os campos, incluindo o caminho da imagem
        $sql = "SELECT idtermos, nome_termo, descricao_termo, tipo_termo, imagem_termo, status, salas_idsalas, usuario_idusuario FROM termos";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        echo json_encode(["success" => true, "data" => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        break;

    case 'PUT':
        // PUT geralmente continua recebendo via JSON
        $data = json_decode(file_get_contents("php://input"));
        try {
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
        $data = json_decode(file_get_contents("php://input"));
        $stmt = $conn->prepare("DELETE FROM termos WHERE idtermos = :id");
        $stmt->execute([':id' => $data->idtermos]);
        echo json_encode(["success" => true]);
        break;
}
?>