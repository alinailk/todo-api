<?php
// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once '../config/database.php';
require_once '../src/Models/TodoModel.php';

$database = new Database();
$db = $database->getConnection();
$todoModel = new TodoModel($db);

// ID kontrolü
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Görevi getir
$todo = $todoModel->getTodoById($id);
if (!$todo) {
    header('Location: index.php');
    exit;
}

// POST isteği geldiğinde güncelleme yap
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'due_date' => $_POST['due_date'] ?? null,
        'priority' => $_POST['priority'] ?? 'medium',
        'status' => $_POST['status'] ?? 'pending'
    ];

    $todoModel->updateTodo($data);
    header('Location: index.php');
    exit;
}

// Görev durumlarının veritabanındaki enum karşılıklarının ekranda doğru görünmesi için
$statusTranslations = [
    'pending' => 'Bekliyor',
    'in_progress' => 'Devam Ediyor',
    'completed' => 'Tamamlandı',
    'cancelled' => 'İptal Edildi'
];
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Görev Düzenle</title>
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
                        <h1 class="text-center mb-4">Görev Düzenle</h1>

                        <form action="edit.php?id=<?php echo $id; ?>" method="post">
                            <div class="mb-3">
                                <label for="title" class="form-label">Başlık</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo htmlspecialchars($todo['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama</label>
                                <textarea class="form-control" id="description" name="description"
                                    rows="3"><?php echo htmlspecialchars($todo['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Bitiş Tarihi</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date"
                                    value="<?php echo $todo['due_date'] ? date('Y-m-d\TH:i', strtotime($todo['due_date'])) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Öncelik</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" <?php echo $todo['priority'] === 'low' ? 'selected' : ''; ?>>
                                        Düşük
                                    </option>
                                    <option value="medium"
                                        <?php echo $todo['priority'] === 'medium' ? 'selected' : ''; ?>>Orta</option>
                                    <option value="high" <?php echo $todo['priority'] === 'high' ? 'selected' : ''; ?>>
                                        Yüksek</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Durum</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending"
                                        <?php echo $todo['status'] === 'pending' ? 'selected' : ''; ?>>Bekliyor</option>
                                    <option value="in_progress"
                                        <?php echo $todo['status'] === 'in_progress' ? 'selected' : ''; ?>>Devam Ediyor
                                    </option>
                                    <option value="completed"
                                        <?php echo $todo['status'] === 'completed' ? 'selected' : ''; ?>>Tamamlandı
                                    </option>
                                    <option value="cancelled"
                                        <?php echo $todo['status'] === 'cancelled' ? 'selected' : ''; ?>>İptal Edildi
                                    </option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Güncelle</button>
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