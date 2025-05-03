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
        // Silinmemiş görevleri döndürür.
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // To do ekleme fonksiyonu.
    public function createTodo($title, $description, $dueDate = null)
    {
        $sql = "INSERT INTO todos (title, description, due_date) VALUES (:title, :description, :due_date)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate
        ]);
    }

    // To do tamamlandı için fonksiyon.
    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET status = :status WHERE id = :id");
        $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }


    // To do silme fonksiyonu.
    public function deleteTodo($id)
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getTodoById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // To do güncelleme fonksiyonu.
    public function updateTodo($id, $title, $description, $dueDate)
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET title = :title, description = :description, due_date = :due_date WHERE id = :id");
        $stmt->execute([
            'title' => $title,
            'description' => $description,
            'due_date' => $dueDate,
            'id' => $id
        ]);
    }

    // Silme işleminde tarihi tutar.
    public function softDeleteTodo($id)
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

}

?>