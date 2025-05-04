<?php

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// OPTIONS isteğine boş yanıt ver ve çık
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$database = new Database();
$db = $database->connect();
$model = new TodoModel($db);

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id)) {
    echo json_encode(["success" => false, "message" => "ID eksik."]);
    exit;
}

$success = $model->deleteTodo($data->id);

if ($success) {
    echo json_encode(["success" => true, "message" => "Görev silindi."]);
} else {
    echo json_encode(["success" => false, "message" => "Görev silinemedi."]);
}

?>