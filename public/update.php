<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once '../config/database.php';
require_once '../src/Models/TodoModel.php';
require_once '../src/Http/Request.php';

use App\Http\Request;

// Veritabanı bağlantısını başlatıyoruz
$database = new Database();
$db = $database->getConnection();
$todoModel = new TodoModel($db);

// POST isteği geldiğinde çalışacak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        // Eğer sadece durum güncellemesi yapılıyorsa (Tamamlandı butonu için)
        if (isset($_POST['id']) && !isset($_POST['title'])) {
            $todoModel->updateStatus($id, 'completed');
        }
        // Eğer görev içeriği güncelleniyorsa
        else if (isset($_POST['title'])) {
            $data = [
                'id' => $id,
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'due_date' => $_POST['due_date'] ?? null,
                'priority' => $_POST['priority'] ?? 'medium',
                'status' => $_POST['status'] ?? 'pending'
            ];

            $todoModel->updateTodo($data);
        }
    }
}

// İşlem tamamlandığında index sayfasına yönlendir
header('Location: index.php');
exit;

// 1. Repository Pattern ekle
class TodoRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll($page = 1, $limit = 10, $sort = 'created_at', $order = 'desc')
    {
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM todos ORDER BY {$sort} {$order} LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// 2. Service Layer ekle
class TodoService
{
    private $repository;

    public function __construct(TodoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createTodo(array $data)
    {
        // İş mantığı ve validasyon
    }

    public function getAllTodos(array $params = [])
    {
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 10;
        $sort = $params['sort'] ?? 'created_at';
        $order = $params['order'] ?? 'desc';

        return $this->repository->findAll($page, $limit, $sort, $order);
    }
}

// 3. Controller yapısını düzenle
class TodoController
{
    private $service;

    public function __construct(TodoService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        // Sayfalama, sıralama ve filtreleme parametrelerini al
        return $this->service->getAllTodos($request->all());
    }
}

// 4. Standart API yanıt formatı
class ApiResponse
{
    public static function success($data, $message = null, $meta = null)
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'meta' => $meta
        ];
    }

    public static function error($message, $errors = null)
    {
        return [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ];
    }
}

?>