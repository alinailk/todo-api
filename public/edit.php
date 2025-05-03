<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

// Veritabanı bağlantısını sağlar.
$pdo = Database::connect();

// Görev ID'sini al
$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}

// TodoModel sınıfının örneğini oluştur
$model = new TodoModel($pdo); // Burada veritabanı bağlantısını doğru şekilde geçiriyoruz.

// ID'ye göre görev verisini al
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
    <?php if ($todo): ?>
        <form action="update.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">

            <label>Başlık:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($todo['title']); ?>" required>

            <label>Açıklama:</label>
            <textarea name="description"><?php echo htmlspecialchars($todo['description']); ?></textarea>

            <label>Bitiş Tarihi:</label>
            <input type="datetime-local" name="due_date"
                value="<?php echo date('Y-m-d\TH:i', strtotime($todo['due_date'])); ?>">

            <button type="submit">Güncelle</button>
        </form>
    <?php else: ?>
        <p>Görev bulunamadı.</p>
    <?php endif; ?>
    <a href="index.php">Geri Dön</a>
</body>

</html>