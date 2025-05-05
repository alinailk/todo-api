<?php
class Database
{
    private $host = "localhost";
    private $db_name = "todo_app";  // Veritabanı adı
    private $username = "root";      // Kullanıcı adı
    private $password = "";          // Şifre
    public $conn;

    // Veritabanı bağlantısını oluştur
    public function getConnection()
    {
        $this->conn = null;

        try {
            // PDO bağlantı seçeneklerini ayarla
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            // PDO ile veritabanı bağlantısını oluştur
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                $options
            );

            // Bağlantıyı test et
            $this->conn->query("SELECT 1");
            
            return $this->conn;

        } catch (PDOException $e) {
            error_log("Veritabanı Bağlantı Hatası: " . $e->getMessage());
            throw new Exception("Veritabanına bağlanılamadı. Lütfen daha sonra tekrar deneyin.");
        }
    }
}
?>
