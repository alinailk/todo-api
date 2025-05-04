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

    // JSON verisini al ve kontrol et
    $input = file_get_contents("php://input");
    error_log("Gelen veri: " . $input); // Debug log

    $data = json_decode($input);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Geçersiz JSON formatı");
    }

    if (!isset($data->id)) {
        echo json_encode([
            "success" => false, 
            "message" => "ID eksik."
        ]);
        exit;
    }

    // ID'nin sayı olduğundan emin ol
    if (!is_numeric($data->id)) {
        echo json_encode([
            "success" => false, 
            "message" => "Geçersiz ID formatı."
        ]);
        exit;
    }

    // Silme işlemini gerçekleştir
    $success = $model->deleteTodo($data->id);

    if ($success) {
        echo json_encode([
            "success" => true, 
            "message" => "Görev başarıyla silindi."
        ]);
    } else {
        echo json_encode([
            "success" => false, 
            "message" => "Görev bulunamadı veya silinemedi."
        ]);
    }

} catch (Exception $e) {
    error_log("Silme hatası: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Bir hata oluştu: " . $e->getMessage()
    ]);
}
?>