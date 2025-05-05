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
        echo "Görev oluşturulurken bir hata oluştu";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Görev Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Yeni Görev Ekle</h1>

                        <form action="create.php" method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Başlık</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Bitiş Tarihi</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Öncelik</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low">Düşük</option>
                                    <option value="medium">Orta</option>
                                    <option value="high">Yüksek</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Görev Ekle</button>
                                <a href="index.php" class="btn btn-secondary">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>