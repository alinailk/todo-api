<?php
class Database // Database sınıfını oluşturur.
{
    private $host = "localhost";  // Veritabanının barındığı sunucunun adresi.
    private $db_name = "todo_app";  // Veritabanı adı.
    private $username = "root";      // Kullanıcı adı.
    private $password = "";          // Şifre.
    public $conn;  // Veritabanı bağlantı objesinin tutulduğu değişken.

    // Veritabanı bağlantısını oluştur
    public function getConnection()
    {
        $this->conn = null;

        try {
            // PDO bağlantı seçeneklerini ayarla
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Hata durumunda PHP'nin bir exception fırlatmasını sağlar.
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Veritabanından alınan sonuçların bir associative array (anahtar-değer) olarak döndürülmesini sağlar.
                PDO::ATTR_EMULATE_PREPARES => false, // PDO'nun yerel veritabanı sürücüsü tarafından sağlanan hazır sorgu işlemlerini kullanmasını sağlar.
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci" // Veritabanına bağlandığında karakter setini utf8mb4 olarak ayarlar.
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

            return $this->conn; // Bağlantı başarılı olursa, PDO bağlantı nesnesi geri döndürülür.

        } catch (PDOException $e) {
            error_log("Veritabanı Bağlantı Hatası: " . $e->getMessage());
            throw new Exception("Veritabanına bağlanılamadı. Lütfen daha sonra tekrar deneyin.");
        }
    }
}

?>