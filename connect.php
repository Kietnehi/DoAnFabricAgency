<?php
<<<<<<< HEAD
$host = 'localhost:3307'; // Thay đổi nếu cần
$db = 'fabric_new';
=======
$host = 'localhost:3306'; // Thay đổi nếu cần
$db = 'fabric_new2';
>>>>>>> 6f5ad255a05685febbe50e5d1d5a7586a6218dc7
$user = 'root'; // Thay đổi nếu cần
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
    exit();
}
