<?php

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../Config/Database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// OPTIONS isteğine boş yanıt ver ve çık
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    $database = new Database();
    $db = $database->connect();
    $model = new TodoModel($db);

    // JSON verisini al
    $input = file_get_contents("php://input");
    error_log("Gelen veri: " . $input); // Debug log

    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Geçersiz JSON formatı");
    }

    if (!isset($data->id) || !isset($data->status)) {
        echo json_encode([
            "success" => false,
            "message" => "ID veya status eksik."
        ]);
        exit;
    }

    // Status güncelleme işlemini gerçekleştir
    $success = $model->updateStatus($data->id, $data->status);

    if ($success) {
        echo json_encode([
            "success" => true,
            "message" => "Görev durumu güncellendi."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Görev durumu güncellenemedi."
        ]);
    }

} catch (Exception $e) {
    error_log("Status güncelleme hatası: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Bir hata oluştu: " . $e->getMessage()
    ]);
}
?>