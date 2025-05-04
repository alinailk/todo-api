<?php
// Gerekli dosyaların içe aktarımı.
require_once __DIR__ . '/../../config/database.php';
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
    $pdo = Database::connect();
    $model = new TodoModel($pdo);

    // JSON veri alma.
    $input = file_get_contents('php://input');
    error_log("Gelen veri: " . $input); // Debug log

    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Geçersiz JSON formatı");
    }

    if (!isset($data['id'], $data['title'], $data['description'], $data['due_date'])) {
        echo json_encode(['success' => false, 'error' => 'Eksik veri']);
        exit;
    }

    // Güncelleme işlemi.
    $success = $model->updateTodo($data);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Görev güncellendi']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Görev güncellenemedi']);
    }

} catch (Exception $e) {
    error_log("Güncelleme hatası: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>