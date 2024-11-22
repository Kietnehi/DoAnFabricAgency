<?php
$host = 'localhost'; // Thay đổi nếu cần
$db = 'fabric_new';
$user = 'root'; // Thay đổi nếu cần
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
    exit();
}
