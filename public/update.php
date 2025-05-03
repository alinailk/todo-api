<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

$id = $_POST['id'] ?? null;

if ($id) {
    $model = new TodoModel($pdo);
    $model->updateStatus($id, 'completed'); // Buraya dikkat!
}

// İşlem bitince anasayfaya yönlendir.
header("Location: index.php");
exit;