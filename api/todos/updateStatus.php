<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

// CORS ayarları
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// JSON veriyi al
$data = json_decode(file_get_contents("php://input"), true);

// Zorunlu alanlar kontrolü.
if (!isset($data['id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(["message" => "Eksik parametreler."]);
    exit;
}

$pdo = Database::connect();
$model = new TodoModel($pdo);

// Güncelleme işlemi
$model->updateStatus($data['id'], $data['status']);

// Başarılı yanıt
echo json_encode(["message" => "Durum güncellendi."]);

?>