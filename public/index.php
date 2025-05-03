<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

$model = new TodoModel($pdo);

// Görev ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $dueDate = $_POST['due_date'] ?? null;

    $model->createTodo($title, $description, $dueDate);
    header('Location: index.php');
    exit;
}

// Görevleri listeleme
$todos = $model->getAllTodos();

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
    <title>Görev Listesi</title>
</head>

<body>
    <h1>Yeni Görev Ekle</h1>
    <form action="index.php" method="post">
        <input type="text" name="title" placeholder="Görev Başlığı" required>
        <textarea name="description" placeholder="Açıklama"></textarea>
        <label for="due_date">Bitiş Tarihi:</label>
        <input type="datetime-local" name="due_date">
        <button type="submit">Ekle</button>
    </form>

    <h2>Görevler</h2>
    <ul>
        <?php foreach ($todos as $todo):
            $translatedStatus = $statusTranslations[$todo['status']] ?? $todo['status'];
            ?>
        <li>
            <?php echo htmlspecialchars($todo['title']); ?>
            (<?php echo $translatedStatus; ?>)
            -
            Bitiş: <?php echo date('d.m.Y H:i', strtotime($todo['due_date'])); ?>
            -
            <a href="edit.php?id=<?php echo $todo['id']; ?>">Düzenle</a>
            -
            <a href="delete.php?id=<?php echo $todo['id']; ?>"
                onclick="return confirm('Bu görevi silmek istediğinize emin misiniz?')">Sil</a>
            -
            <?php if ($todo['status'] !== 'completed'): ?>
            <form action="update.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                <button type="submit">Tamamlandı</button>
            </form>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>