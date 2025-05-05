<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once '../config/database.php';
require_once '../src/Models/TodoModel.php';

$database = new Database();
$db = $database->getConnection();
$todoModel = new TodoModel($db);

$id = isset($_GET['id']) ? $_GET['id'] : die('ID not provided');
$todo = $todoModel->getTodoById($id);

if (!$todo) {
    die('Todo not found');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'due_date' => $_POST['due_date'],
        'priority' => $_POST['priority'],
        'status' => $_POST['status']
    ];

    if ($todoModel->updateTodo($id, $data['title'], $data['description'], $data['due_date'], $data['status'])) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error updating todo";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Todo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="text-center mb-4">Edit Todo</h1>

                        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo htmlspecialchars($todo['title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description"
                                    rows="3"><?php echo htmlspecialchars($todo['description']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date"
                                    value="<?php echo date('Y-m-d', strtotime($todo['due_date'])); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" <?php echo $todo['priority'] === 'low' ? 'selected' : ''; ?>>Low
                                    </option>
                                    <option value="medium" <?php echo $todo['priority'] === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo $todo['priority'] === 'high' ? 'selected' : ''; ?>>
                                        High</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="pending" <?php echo $todo['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="completed" <?php echo $todo['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Todo</button>
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
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