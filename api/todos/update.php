<?php

// Gerekli dosyaların içe aktarımı.
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

$pdo = Database::connect();
$model = new TodoModel($pdo);

// JSON veri alma.
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['id'], $data['title'], $data['description'], $data['due_date'])) {
    echo json_encode(['error' => 'Eksik veri']);
    exit;
}

// Güncelleme işlemi.
$model->updateTodo($data['id'], $data['title'], $data['description'], $data['due_date'], $data['status']);
echo json_encode(['success' => 'Görev güncellendi']);

?>