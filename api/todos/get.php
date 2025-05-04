<?php
// Veritabanı ve model dosyalarını doğru yerden çağır.
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../src/Models/TodoModel.php';

// Veritabanı bağlantısını oluştur.
$pdo = Database::connect();
$model = new TodoModel($pdo);

// Tüm görevleri getir.
$todos = $model->getAllTodos();

// JSON olarak yanıtla.
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // React için gerekli
echo json_encode($todos);
?>