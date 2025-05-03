<?php
class TodoModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllTodos()
    {
        // Silinmemiş görevleri döndürür.
        $stmt = $this->pdo->prepare("SELECT * FROM todos WHERE deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTodo($title): void
    {

        $sql = "INSERT INTO todos (title) VALUES (:title)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['title' => $title]);
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE todos SET status = :status WHERE id = :id");
        $stmt->execute([
            'status' => $status,
            'id' => $id
        ]);
    }

}

?>