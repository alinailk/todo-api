<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

// Veritabanı bağlantısını sağlar.
$pdo = Database::connect();

// ID parametresini al
$id = $_GET['id'] ?? null;

// Eğer ID geçerli ise
if ($id) {
    // TodoModel sınıfından nesne oluştur
    $model = new TodoModel($pdo);
    // Görevi sil
    $model->deleteTodo($id);
}

// Silme işlemi sonrası index sayfasına yönlendir
header('Location: index.php');
exit;
?>