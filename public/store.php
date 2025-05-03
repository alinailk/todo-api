<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

// Formdan gelen başlık verisini al.
$title = $_POST['title'] ?? '';

// Başlık verisi boş değilse kayıt yap.
if (!empty($title)) {
    $model = new TodoModel($pdo);
    $model->createTodo($title); // Veritabanına ekler.
}

// İşlem bitince anasayfaya yönlendir
header("Location: index.php");
exit;

?>