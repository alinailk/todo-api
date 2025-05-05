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
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $dueDate = $_POST['due_date'] ?? null;

    $todoModel->createTodo($title, $description, $dueDate);
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .todo-item {
        transition: all 0.3s ease;
    }

    .todo-item:hover {
        transform: translateX(5px);
    }

    .completed {
        text-decoration: line-through;
        opacity: 0.7;
    }

    .priority-high {
        border-left: 4px solid #dc3545;
    }

    .priority-medium {
        border-left: 4px solid #ffc107;
    }

    .priority-low {
        border-left: 4px solid #28a745;
    }
    </style>
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Todo App</h1>

                        <!-- Add Todo Form -->
                        <form action="create.php" method="POST" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" name="title" class="form-control" placeholder="Task title"
                                        required>
                                </div>
                                <div class="col-md-3">
                                    <select name="priority" class="form-select" required>
                                        <option value="low">Low Priority</option>
                                        <option value="medium">Medium Priority</option>
                                        <option value="high">High Priority</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="due_date" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="description" class="form-control" placeholder="Task description"
                                        rows="2"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-plus"></i> Add Task
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Filter Buttons -->
                        <div class="btn-group w-100 mb-4">
                            <a href="?status=all"
                                class="btn btn-outline-primary <?php echo $status === 'all' ? 'active' : ''; ?>">All</a>
                            <a href="?status=active"
                                class="btn btn-outline-primary <?php echo $status === 'active' ? 'active' : ''; ?>">Active</a>
                            <a href="?status=completed"
                                class="btn btn-outline-primary <?php echo $status === 'completed' ? 'active' : ''; ?>">Completed</a>
                        </div>

                        <!-- Todo List -->
                        <div class="todo-list">
                            <?php if ($todos && count($todos) > 0): ?>
                            <?php foreach ($todos as $todo): ?>
                            <div
                                class="todo-item card mb-3 priority-<?php echo $todo['priority']; ?> <?php echo $todo['status'] === 'completed' ? 'completed' : ''; ?>">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1"><?php echo htmlspecialchars($todo['title']); ?>
                                            </h5>
                                            <p class="card-text text-muted mb-0">
                                                <?php echo htmlspecialchars($todo['description']); ?></p>
                                            <small class="text-muted">
                                                Due: <?php echo date('M d, Y', strtotime($todo['due_date'])); ?>
                                            </small>
                                        </div>
                                        <div class="btn-group">
                                            <?php if ($todo['status'] !== 'completed'): ?>
                                            <a href="update_status.php?id=<?php echo $todo['id']; ?>&status=completed"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <?php else: ?>
                                            <a href="update_status.php?id=<?php echo $todo['id']; ?>&status=pending"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                            <?php endif; ?>
                                            <a href="edit.php?id=<?php echo $todo['id']; ?>"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $todo['id']; ?>"
                                                class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <div class="text-center text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                                <p>No tasks found</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>