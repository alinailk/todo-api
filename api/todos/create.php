<?php

// Gerekli dosyaların içe aktarımı.
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['title'])) {
    http_response_code(400);
    echo json_encode(["error" => "Eksik veri"]);
    exit;
}

$title = $data['title'];
$description = $data['description'] ?? null;
$due_date = $data['due_date'] ?? null;

$model = new TodoModel($pdo);
$model->createTodo($title, $description, $due_date);

echo json_encode(["message" => "Görev başarıyla eklendi"]);