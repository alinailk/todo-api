<?php

// Veritabanı bağlantısı için database.php dosyasını dahil et.
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';
;

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
        <button type="submit">Ekle</button>
    </form>
    <!-- Görev listesi -->
    <ul>
        <?php foreach ($todos as $todo): ?>
        <li style="list-style-type: none;">
            <?php echo htmlspecialchars($todo['title']); ?> -
            <?php
                switch ($todo['status']) {
                    case 'completed':
                        echo 'Tamamlandı';
                        break;
                    case 'in_progress':
                        echo 'Devam ediyor';
                        break;
                    case 'cancelled':
                        echo 'İptal edildi';
                        break;
                    default:
                        echo 'Bekliyor...';
                }
                ?>

            <?php if ($todo['status'] !== 'completed'): ?>
            <form action="update.php" method="post" style="display:inline;">
                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                <button type="submit">Tamamla</button>
            </form>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>