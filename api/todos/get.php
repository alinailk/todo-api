<?php

// Gerekli dosyaları dahil et
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

// CORS ve JSON header'ları ayarla
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$model = new TodoModel($pdo);

// Veritabanından görevleri al
$todos = $model->getAllTodos();

// JSON çıktısı olarak gönder
echo json_encode($todos);