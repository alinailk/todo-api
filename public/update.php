<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $model = new TodoModel($pdo);
        $model->updateStatus($id, 'completed'); // Durumu 'completed' olarak güncelliyoruz
    }
}

header('Location: index.php');
exit;