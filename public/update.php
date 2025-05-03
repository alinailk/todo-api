<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

$id = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if ($id && $status) {
    $model = new TodoModel($pdo);
    $model->updateStatus($id, $status);  // Status'u update et
}

header("Location: index.php");
exit;

?>