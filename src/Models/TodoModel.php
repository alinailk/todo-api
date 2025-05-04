<?php
class TodoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // To do listeleme fonksiyonu.
    public function getAllTodos()
    {
        // Silinmemiş ve tamamlanmamış (pending) görevleri getiriyoruz
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // To do ekleme fonksiyonu.
    public function createTodo($title, $description, $dueDate = null, $priority = 'medium')
    {
        try {
            $sql = "INSERT INTO todos (title, description, due_date, status, priority) 
                    VALUES (:title, :description, :due_date, 'pending', :priority)";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                'title' => $title,
                'description' => $description,
                'due_date' => $dueDate,
                'priority' => $priority
            ]);

            if ($result) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Görev ekleme hatası: " . $e->getMessage());
            return false;
        }
    }

    // To do durumunu güncelleme fonksiyonu.
    public function updateStatus($id, $status)
    {
        try {
            // Önce görevin var olup olmadığını kontrol et
            $checkStmt = $this->pdo->prepare("SELECT id FROM todos WHERE id = :id AND deleted_at IS NULL");
            $checkStmt->execute(['id' => $id]);

            if ($checkStmt->rowCount() === 0) {
                return false;
            }

            // Status'u güncelle
            $stmt = $this->pdo->prepare("UPDATE todos SET status = :status WHERE id = :id");
            $result = $stmt->execute([
                'status' => $status,
                'id' => $id
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Status güncelleme hatası: " . $e->getMessage());
            return false;
        }
    }

    // To do silme fonksiyonu.

    public function deleteTodo($id)
    {
        try {
            // Önce görevin var olup olmadığını kontrol et
            $checkStmt = $this->pdo->prepare("SELECT id FROM todos WHERE id = :id AND deleted_at IS NULL");
            $checkStmt->execute(['id' => $id]);

            if ($checkStmt->rowCount() === 0) {
                return false;
            }

            // Görevi soft delete yap
            $stmt = $this->pdo->prepare("UPDATE todos SET deleted_at = NOW() WHERE id = :id");
            $result = $stmt->execute(['id' => $id]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Silme hatası: " . $e->getMessage());
            return false;
        }
    }


    // Görev ID'sine göre verileri al.
    public function getTodoById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // To do güncelleme fonksiyonu (Başlık, Açıklama, Bitiş Tarihi ve Durum).
    public function updateTodo($id, $title, $description, $dueDate, $status)
    {
        try {
            $stmt = $this->pdo->prepare("
            UPDATE todos 
            SET title = :title, 
                description = :description, 
                due_date = :due_date, 
                status = :status 
            WHERE id = :id
        ");

            $result = $stmt->execute([
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'due_date' => $dueDate,
                'status' => $status
            ]);

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Güncelleme hatası: " . $e->getMessage());
            return false;
        }
    }

    // Silme işleminde tarihi tutar.
    public function softDeleteTodo($id)
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>