<?php

// Hata raporlamayı aç
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS ayarları
header('Access-Control-Allow-Origin: *'); // Herhangi bir kaynaktan gelen isteklere izin verir.
header('Access-Control-Allow-Methods: GET'); // Sadece GET isteklerini kabul eder.
header('Access-Control-Allow-Headers: Content-Type'); // İsteklerde Content-Type başlığına izin verilir.
header('Content-Type: application/json'); // Yanıtın JSON formatında olduğu belirtilir.

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

try {
    // Veritabanına bağlan
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        http_response_code(500); // Bağlantı başarılı olmazsa 500 kodu sunucu hatası döndürür.
        echo json_encode(['success' => false, 'message' => 'Veritabanına bağlanılamadı.']);
        exit;
    }

    // Model üzerinden verileri al
    $model = new TodoModel($pdo);
    $todos = $model->getAllTodos();

    http_response_code(200); // Bağlantı başarılı 200 kodunu döndürür.
    echo json_encode($todos);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Sunucu hatası: ' . $e->getMessage()
    ]);
}

?>