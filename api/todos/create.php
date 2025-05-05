<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

// Veritabanı bağlantısını oluşturur.
$db = new Database();
$pdo = $db->getConnection(); // Veritabanı bağlantısını al

// Bağlantıyı kontrol etmek için.
if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Veritabanına bağlanılamadı.']);
    exit;
}

// Modeli başlatır.
$model = new TodoModel($pdo);

// Gelen JSON verisini alır.
$data = json_decode(file_get_contents("php://input"), true);

// Gelen verilerin doğruluğunu kontrol eder.
if (
    isset($data['title']) &&
    isset($data['description']) &&
    isset($data['due_date']) &&
    isset($data['priority'])
) {
    // Varsayılan status değerini ekler.
    $data['status'] = 'pending';

    // Yeni görevi veritabanına ekler.
    $result = $model->createTodo($data);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Görev başarıyla eklendi.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Görev eklenirken bir hata oluştu.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Eksik veri.']);
}
?>