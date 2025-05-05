<?php
require_once '../config/database.php';
require_once '../src/Models/TodoModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $todoModel = new TodoModel($db);

    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'due_date' => $_POST['due_date'],
        'priority' => $_POST['priority'],
        'status' => 'pending'
    ];

    if ($todoModel->createTodo($data)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error creating todo";
    }
}
?>