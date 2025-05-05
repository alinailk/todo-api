<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once '../config/database.php';
require_once '../src/Models/TodoModel.php';

$database = new Database();
$db = $database->getConnection();
$todoModel = new TodoModel($db);

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$todos = $todoModel->getAllTodos($status);

// Görev ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'due_date' => $_POST['due_date'] ?? null,
        'priority' => $_POST['priority'] ?? 'medium',
        'status' => 'pending'
    ];

    $todoModel->createTodo($data);
    header('Location: index.php');
    exit;
}

// Görevleri listeleme
$todos = $todoModel->getAllTodos();

// Görev durumlarının veritabanındaki enum karşılıklarının ekranda doğru görünmesi için.
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
    <title>Görev Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        padding: 20px;
        background-color: #f8f9fa;
    }

    .todo-item {
        margin-bottom: 10px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .status-badge {
        font-size: 0.8em;
        padding: 5px 10px;
        border-radius: 15px;
    }

    .status-pending {
        background-color: #ffd700;
    }

    .status-in_progress {
        background-color: #87ceeb;
    }

    .status-completed {
        background-color: #90ee90;
    }

    .status-cancelled {
        background-color: #ff6b6b;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mb-4">Görev Yönetimi</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title h5">Yeni Görev Ekle</h2>
                <form action="index.php" method="post" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="title" placeholder="Görev Başlığı" required>
                    </div>
                    <div class="col-md-3">
                        <textarea class="form-control" name="description" placeholder="Açıklama"></textarea>
                    </div>
                    <div class="col-md-2">
                        <input type="datetime-local" class="form-control" name="due_date">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="priority" required>
                            <option value="">Öncelik Seçin</option>
                            <option value="low">Düşük</option>
                            <option value="medium">Orta</option>
                            <option value="high">Yüksek</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Ekle</button>
                    </div>
                </form>
            </div>
        </div>

        <h2 class="mb-3">Görevler</h2>
        <div class="todo-list">
            <?php foreach ($todos as $todo):
                $translatedStatus = $statusTranslations[$todo['status']] ?? $todo['status'];
                $statusClass = 'status-' . $todo['status'];
                ?>
            <div class="todo-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="h6 mb-1"><?php echo htmlspecialchars($todo['title']); ?></h3>
                        <p class="mb-1 small text-muted"><?php echo htmlspecialchars($todo['description']); ?></p>
                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $translatedStatus; ?></span>
                        <span class="badge bg-<?php
                            echo $todo['priority'] === 'high' ? 'danger' :
                                ($todo['priority'] === 'medium' ? 'warning' : 'info');
                            ?> ms-2">
                            <?php
                                echo $todo['priority'] === 'high' ? 'Yüksek' :
                                    ($todo['priority'] === 'medium' ? 'Orta' : 'Düşük');
                                ?>
                        </span>
                        <small class="ms-2">Bitiş:
                            <?php echo date('d.m.Y H:i', strtotime($todo['due_date'])); ?></small>
                    </div>
                    <div class="btn-group">
                        <a href="edit.php?id=<?php echo $todo['id']; ?>"
                            class="btn btn-sm btn-outline-primary">Düzenle</a>
                        <a href="delete.php?id=<?php echo $todo['id']; ?>"
                            onclick="return confirm('Bu görevi silmek istediğinize emin misiniz?')"
                            class="btn btn-sm btn-outline-danger">Sil</a>
                        <?php if ($todo['status'] !== 'completed'): ?>
                        <form action="update.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                            <button type="submit" class="btn btn-sm btn-outline-success">Tamamlandı</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>