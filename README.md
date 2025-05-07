🛠️ Todo API (PHP)

Bu repo, React tabanlı bir todo uygulaması için geliştirilmiş saf PHP ve REST API içerir.

📂 Projenin Klasör Yapısı

📁 todo-api/
    📁 api/                        → API işlemlerinin yer aldığı klasör
        📁 todos/                  → Todo işlemleri için PHP dosyaları
            📄 create.php          → Todo eklemek için API dosyası
            📄 get.php             → Todo listelemek için API dosyası
            📄 delete.php          → Todo silmek için API dosyası
            📄 update.php          → Todo içeriğini güncellemek için API dosyası
            📄 updateStatus.php    → Todo durumunu güncellemek için API dosyası
			
    📁 config/                     → Konfigürasyon dosyaları
        📄 database.php            → Veritabanı bağlantı dosyası
		
    📁 public/                     → Public klasörü (web'e açık dosyalar)
        📄 create.php              → Todo eklemek için kullanıcı arayüzü içeren PHP dosyası
        📄 delete.php              → Todo silmek için kullanıcı arayüzü içeren PHP dosyası
        📄 edit.php                → Todo düzenlemek için kullanıcı arayüzü içeren PHP dosyası
        📄 index.php               → Ana sayfa
        📄 store.php               → Todo ekleme işlemi için veri gönderimi
        📄 update.php              → Todo güncelleme işlemi için kullanıcı arayüzü içeren PHP dosyası
		
    📁 src/                        → Uygulamanın kaynak kodları
        📁 Http/                   → HTTP ile ilgili sınıflar
            📄 Request.php         → HTTP isteklerini yöneten sınıf
			
        📁 Models/                 → Veritabanı ile etkileşimde kullanılan modeller
            📄 TodoModel.php       → Todo verilerini yöneten model


⚙️ Kullanılan Teknolojiler

	•PHP (PDO ile MySQL bağlantısı)
	•MySQL veritabanı
	•JSON formatında REST API
	•CORS desteği
	•Soft delete sistemi ("deleted_at")


🧪 API Uç Noktaları

| GET    | "/todos/get.php"   | Tüm görevleri listeler |
| POST   | "/todos/create.php"| Yeni görev ekler        |
| POST   | "/todos/update.php"| Görev düzenler veya tamamlandı yapar |
| POST   | "/todos/delete.php"| Görevi soft delete yapar |

🧰 Kurulum

1. Bu klasörü "htdocs" içine koyun.
2. "todo_app" adında veritabanını oluşturun.
3. "todos" adında gerekli tabloyu oluşturun:

** SQL Tablo Oluşturma

CREATE TABLE tasks (
  	id INT AUTO_INCREMENT PRIMARY KEY,
  	title VARCHAR(255) NOT NULL,
  	description TEXT,
  	status ENUM('pending', 'completed') DEFAULT 'pending',
  	priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
  	due_date DATETIME,
  	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  	deleted_at DATETIME DEFAULT NULL
);

4. database.php dosyasında veritabanı adı ve şifre bilgilerini kendi ortamınıza göre düzenleyin.

** API Dökümantasyonu:

API uç noktaları için todo-api/api/todos/README.md dosyasına göz atabilirsiniz.