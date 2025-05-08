# 📌 Todo API - Teknik Dokümantasyon

Bu API, temel CRUD (Create, Read, Update, Delete) işlemleri gerçekleştiren bir PHP tabanlı Todo uygulamasının backend servislerini sağlar.

## 🌐 Base URL

Tüm istekler şu URL üzerinden gönderilmelidir:

http://localhost/api/todos/

### 1. Yeni Görev Ekleme (create.php)

| **URL**         | .../api/todos/create.php                                                                                                                                                                   |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Method**      | POST                                                                                                                                                                                       |
| **Description** | Bu endpoint, yeni bir todo (görev) eklemek için kullanılır. Görev başlığı, açıklaması, son tarihi ve önceliği gereklidir. Eğer görev başarılı bir şekilde eklenirse, success yanıtı döner. |

{
  "title": "Görev Başlığı",
  "description": "Görev Açıklaması",
  "due_date": "2025-05-01T12:00:00",
  "priority": "high"
}

| **Parametre**   | **Tip** | **Açıklama**                                 |
| --------------- | ------- | -------------------------------------------- |
| **title**       | string  | Görev başlığı.                               |
| **description** | string  | Görev açıklaması.                            |
| **due\_date**   | string  | Görev tamamlanması gereken tarih (ISO 8601). |
| **priority**    | string  | Görev önceliği. (low, medium, high)          |


Başarılı Yanıt:

HTTP Status Code: 200 OK

Yanıt Yapısı:
{
  "success": true,
  "message": "Görev başarıyla eklendi."
}

Hata Yanıtı:

HTTP Status Code: 400 Bad Request

Yanıt Yapısı:

{
  "success": false,
  "message": "Eksik veri."
}


### 2. Görev Silme (delete.php)

| **URL**         | .../api/todos/delete.php                                                                                                                                    |
| --------------- | ----------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Method**      | POST                                                                                                                                                        |
| **Description** | Bu endpoint, bir todo (görev) silmek için kullanılır. id parametresi gereklidir. Görev ID’si geçerli değilse veya silme işlemi başarılı olmazsa hata döner. |

{
  "id": 1
}

| **Parametre** | **Tip** | **Açıklama**           |
| ------------- | ------- | ---------------------- |
| **id**        | integer | Silinecek görev ID'si. |


Başarılı Yanıt:

HTTP Status Code: 200 OK

Yanıt Yapısı:

{
  "success": true,
  "message": "Görev başarıyla silindi."
}


Hata Yanıtı:

HTTP Status Code: 400 Bad Request

{
  "success": false,
  "message": "ID eksik."
}

Geçersiz ID Formatı:

{
  "success": false,
  "message": "Geçersiz ID formatı."
}

### 3. Tüm Görevleri Listeleme (get.php)

| **URL**         | .../api/todos/get.php                                                                    |
| --------------- | ---------------------------------------------------------------------------------------- |
| **Method**      | GET                                                                                      |
| **Description** | Bu endpoint, veritabanındaki tüm görevleri listeler. Tüm görevler JSON formatında döner. |


Başarılı Yanıt:

HTTP Status Code: 200 OK

Yanıt Yapısı:

[
  {
    "id": 1,
    "title": "Görev Başlığı",
    "description": "Görev Açıklaması",
    "status": "pending",
    "priority": "high",
    "due_date": "2025-05-01T12:00:00"
  },
  {
    "id": 2,
    "title": "Başka Bir Görev",
    "description": "Açıklama",
    "status": "completed",
    "priority": "medium",
    "due_date": "2025-05-03T18:00:00"
  }
]

Hata Yanıtı:

HTTP Status Code: 500 Internal Server Error

Yanıt Yapısı:

{
  "success": false,
  "message": "Sunucu hatası: Veritabanına bağlanılamadı."
}

### 4. Görev Güncelleme (update.php)

| **URL**         | .../api/todos/update.php                                                                                                             |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------------ |
| **Method**      | POST                                                                                                                                 |
| **Description** | Bu endpoint, mevcut bir todo’yu günceller. Güncellenmesi gereken görev ID'si, başlık, açıklama ve son tarih gibi veriler gereklidir. |


{
  "id": 1,
  "title": "Yeni Başlık",
  "description": "Yeni Açıklama",
  "due_date": "2025-05-15T12:00:00"
}

| **Parametre**   | **Tip** | **Açıklama**                                            |
| --------------- | ------- | ------------------------------------------------------- |
| **id**          | integer | Güncellenecek görev ID’si.                              |
| **title**       | string  | Görev başlığı.                                          |
| **description** | string  | Görev açıklaması.                                       |
| **due\_date**   | string  | Görev tamamlanması gereken tarih (ISO 8601 formatında). |


Başarılı Yanıt:

HTTP Status Code: 200 OK

Yanıt Yapısı:

{
  "success": true,
  "message": "Görev güncellendi."
}

Hata Yanıtı:

HTTP Status Code: 400 Bad Request

Yanıt Yapısı:

{
  "success": false,
  "error": "Eksik veri"
}

 ### 5. Görev Durumu Güncelleme (updateStatus.php)
 
| **URL**         | .../api/todos/updateStatus.php                                                                                                                         |
| --------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------ |
| **Method**      | POST                                                                                                                                                   |
| **Description** | Bu endpoint, mevcut bir todo’nun durumunu günceller. id ve status parametreleri gereklidir. Görev durumu (pending, completed) gibi değerleri alabilir. |

{
  "id": 1,
  "status": "completed"
}

| **Parametre** | **Tip** | **Açıklama**                              |
| ------------- | ------- | ----------------------------------------- |
| **id**        | integer | Durumu güncellenmesi gereken görev ID’si. |
| **status**    | string  | Yeni görev durumu (pending, completed).   |


Başarılı Yanıt:

HTTP Status Code: 200 OK

Yanıt Yapısı:

{
  "success": true,
  "message": "Görev durumu güncellendi."
}

Hata Yanıtı:

HTTP Status Code: 400 Bad Request

Yanıt Yapısı:

{
  "success": false,
  "message": "ID veya status eksik."
}

