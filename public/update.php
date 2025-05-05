<?php

// Veritabanı bağlantısı ve model dosyasının dahil edilmesi
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/TodoModel.php';

// Veritabanı bağlantısını başlatıyoruz
$pdo = Database::connect();

// POST isteği geldiğinde çalışacak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $model = new TodoModel($pdo);

        // Eğer sadece durum güncellemesi yapılıyorsa (Tamamlandı butonu için)
        if (isset($_POST['status']) && !isset($_POST['title'])) {
            $model->updateStatus($id, 'completed');
        }
        // Eğer görev içeriği güncelleniyorsa
        else if (isset($_POST['title'])) {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $dueDate = $_POST['due_date'] ?? null;

            // Mevcut görevin durumunu al
            $currentTodo = $model->getTodoById($id);
            $currentStatus = $currentTodo['status'] ?? 'pending';

            $model->updateTodo($id, $title, $description, $dueDate, $currentStatus);
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
        // Sayfalama ve sıralama ile todo'ları getir
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