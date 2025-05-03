<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

// Silme işlemi
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $model = new TodoModel($pdo);
    $model->deleteTodo($id);
}

header('Location: index.php');
exit;