<?php

// Veritabanı bağlantısı için database.php dosyasını dahil et.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

// To do modelini başlat.
$model = new TodoModel($pdo);

// Tüm görevleri veritabanından çek.
$todos = $model->getAllTodos();

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
</head>

<body>
    <h1>Todo List</h1>
    <!-- Yeni görev ekleme formu -->
    <form action="store.php" method="post">
        <input type="text" name="title" placeholder="Yeni görev ekle..." required>
        <textarea name="description" placeholder="Görev açıklaması..."></textarea>
        <button type="submit">Ekle</button>
    </form>
    <!-- Görev listesi -->
    <ul>
        <?php foreach ($todos as $todo): ?>
        <li>
            <?php echo htmlspecialchars($todo['title']); ?>
            <?php
                $status = $todo['status'] === 'completed' ? 'Tamamlandı' : 'Bekliyor...';
                echo " > " . $status;
                ?>

            <!-- Tamamlandı butonu -->
            <?php if ($todo['status'] !== 'completed'): ?>
            <form action="update.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                <input type="hidden" name="status" value="completed"> <!-- Status 'completed' olarak gönderiliyor -->
                <button type="submit">Tamamlandı</button>
            </form>
            <?php endif; ?>

            <!-- Silme butonu -->
            <form action="delete.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                <button type="submit" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
            </form>

            <!-- Düzenleme linki -->
            <a href="edit.php?id=<?php echo $todo['id']; ?>">Düzenle</a>
        </li>
        <?php endforeach; ?>

    </ul>

</body>

</html>