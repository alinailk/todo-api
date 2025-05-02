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
}

?>