<?php
class TodoModel
{
    private $conn; // Veritabanı bağlantısını tutar.
    private $table_name = "todos"; // Modelin bağlandığı tablonun adı.

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Tüm todoları listelemek için.
    public function getAllTodos($status = 'all')
    {
        try {
            $query = "SELECT * FROM " . $this->table_name;

            switch ($status) {
                case 'active':
                    $query .= " WHERE status = 'pending'";
                    break;
                case 'completed':
                    $query .= " WHERE status = 'completed'";
                    break;
                case 'deleted':
                    $query .= " WHERE status = 'deleted'";
                    break;
                default:
                    $query .= " WHERE status != 'deleted'";
            }

            $query .= " ORDER BY created_at DESC"; // Sonuçlar oluşturulma tarihine göre azalan sırayla listelenir.

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return false;
        }
    }

    // To do ekleme işlemi için.
    public function createTodo($data)
    {
        try {
            $query = "INSERT INTO " . $this->table_name . "
                (title, description, due_date, priority, status)
                VALUES
                (:title, :description, :due_date, :priority, :status)";

            $stmt = $this->conn->prepare($query);
            // prepare ve bindParam kullanılarak SQL sorgusunda parametreler yerine değerler bağlanır. SQL enjeksiyon saldırılarına karşı koruma sağlar.
            $stmt->bindParam(":title", $data['title']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":due_date", $data['due_date']);
            $stmt->bindParam(":priority", $data['priority']);
            $stmt->bindParam(":status", $data['status']);

            if ($stmt->execute()) {
                $lastId = $this->conn->lastInsertId();

                // Yeni eklenen görevi geri döndür
                $selectStmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE id = :id");
                $selectStmt->bindParam(":id", $lastId);
                $selectStmt->execute();

                return $selectStmt->fetch(PDO::FETCH_ASSOC);
            }

            return false;
        } catch (PDOException $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return false;
        }
    }

    // To do düzenleme fonksiyon kodu.
    public function updateTodo($data)
    {
        try {
            $query = "UPDATE " . $this->table_name . "
                SET
                    title = :title,
                    description = :description,
                    due_date = :due_date,
                    priority = :priority,
                    status = :status
                WHERE
                    id = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $data['id']);
            $stmt->bindParam(":title", $data['title']);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":due_date", $data['due_date']);
            $stmt->bindParam(":priority", $data['priority']);
            $stmt->bindParam(":status", $data['status']);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return false;
        }
    }

    // To do tamamlandı olması için.
    public function updateStatus($id, $status)
    {
        try {
            $query = "UPDATE " . $this->table_name . "
                    SET status = :status
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":status", $status);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return false;
        }
    }

    // To do silme işlemi için.
    public function deleteTodo($id)
    {
        try {
            $query = "UPDATE " . $this->table_name . "
                    SET status = 'deleted', deleted_at = CURRENT_TIMESTAMP
                    WHERE id = :id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);

            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return false;
        }
    }

    // ID'ye göre todoları çekmek için.
    public function getTodoById($id)
    {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Veritabanı hatası: " . $e->getMessage());
            return false;
        }
    }
}

?>