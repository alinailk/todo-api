<?php

// Veritabanı bağlantı ayarları.

$host = 'localhost';
$dbname = 'todo_app';
$username = 'root';
$password = '';

// PDO ile veritabanı bağlantısı

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Bağlantı başarısız olduğunda mesaj döner.
    die('Veritabanı bağlantısı başarısız.' . $e->getMessage());
}

?>