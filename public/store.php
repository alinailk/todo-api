<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

$title = $_POST['title'] ?? '';  // Başlık
$description = $_POST['description'] ?? '';  // Açıklama

// Başlık ve açıklama verileri boş değilse kayıt yap
if (!empty($title)) {
    $model = new TodoModel($pdo);
    $model->createTodo($title, $description);
}

header("Location: index.php");
exit;

?>