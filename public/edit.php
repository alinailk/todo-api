<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

$model = new TodoModel($pdo);
$todo = $model->getTodoById($id);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Görev Düzenle</title>
</head>

<body>
    <h1>Görev Düzenle</h1>
    <form action="update.php" method="post">
        <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">

        <label>Başlık:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($todo['title']); ?>" required>

        <label>Açıklama:</label>
        <textarea name="description"><?php echo htmlspecialchars($todo['description']); ?></textarea>

        <label>Bitiş Tarihi:</label>
        <input type="datetime-local" name="due_date"
            value="<?php echo $todo['due_date'] ? date('Y-m-d\TH:i', strtotime($todo['due_date'])) : ''; ?>">

        <button type="submit">Güncelle</button>
    </form>
    <a href="index.php">Geri Dön</a>
</body>

</html>