<?php
// Hata raporlamayı aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS ayarları
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

try {
    // Veritabanına bağlan
    $db = new Database();
    $pdo = $db->getConnection(); // ← HATA BURADAYDI, düzeltildi

    if (!$pdo) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Veritabanına bağlanılamadı.']);
        exit;
    }

    // Model üzerinden verileri al
    $model = new TodoModel($pdo);
    $todos = $model->getAllTodos();

    http_response_code(200);
    echo json_encode($todos);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Sunucu hatası: ' . $e->getMessage()
    ]);
}
?>
