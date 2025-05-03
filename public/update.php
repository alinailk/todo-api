<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

// Veritabanı bağlantısını başlatıyoruz
$pdo = Database::connect();

// POST isteği geldiğinde çalışacak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null; // Görev ID'sini al

    // Eğer ID geçerli ise
    if ($id) {
        // TodoModel nesnesini oluşturuyoruz
        $model = new TodoModel($pdo);

        // Görev durumu güncelleniyor (başlık ve açıklama korunuyor)
        $model->updateStatus($id, 'completed');
    }
}

// İşlem tamamlandığında index sayfasına yönlendir
header('Location: index.php');
exit;

?>