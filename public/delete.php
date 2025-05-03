<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $model = new TodoModel($pdo);
        $model->deleteTodo($id);
    }
}

// İşlem bitince anasayfaya yönlendir.
header("Location: index.php");
exit;

?>