<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

// Veritabanı bağlantısını oluşturur.
$db = new Database();
$pdo = $db->connect();

	// Veritabanı bağlantı kontrolü.
	if (!$pdo) {
    echo json_encode(['success' => false, 'message' => 'Veritabanına bağlanılamadı.']);
    exit;
	}


// Modeli başlatır.
$model = new TodoModel($pdo);

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['title']) &&
    isset($data['description']) &&
    isset($data['due_date']) &&
    isset($data['priority'])
) {
    // Varsayılan status değerini ekle
    $data['status'] = 'pending';
    
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