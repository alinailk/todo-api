<?php
require_once '../config/database.php';
require_once '../src/Models/TodoModel.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $todoModel = new TodoModel($db);

    $id = $_GET['id'];

    if ($todoModel->deleteTodo($id)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Görev silinirken bir hata oluştu";
    }
} else {
    header('Location: index.php');
    exit;
}
?>