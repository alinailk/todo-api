<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

try {
    // Veritabanı bağlantısını oluşturur.
    $db = new Database();
    $pdo = $db->connect();

    // Modeli başlatır.
    $model = new TodoModel($pdo);

    $data = json_decode(file_get_contents("php://input"), true);

    if (
        isset($data['title']) &&
        isset($data['description']) &&
        isset($data['due_date'])
    ) {
        $result = $model->createTodo(
            $data['title'],
            $data['description'],
            $data['due_date']
        );

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Görev başarıyla eklendi.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Görev eklenirken bir hata oluştu.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Eksik veri.']);
    }
} catch (Exception $e) {
    error_log("Hata: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Bir hata oluştu: ' . $e->getMessage()]);
}

?>