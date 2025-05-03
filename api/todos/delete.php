<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

$pdo = Database::connect();
$model = new TodoModel($pdo);

// JSON veriyi al
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// ID kontrolü
if (!isset($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID eksik']);
    exit;
}

// Silme işlemi
$model->deleteTodo($data['id']);
echo json_encode(['success' => 'Görev silindi']);