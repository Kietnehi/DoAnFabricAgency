<?php
require 'connect.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $supplierId = $_GET['id'];
    try {
        $sql = "SELECT ProductCode, ProductName, Quantity FROM products WHERE SupplierID = :supplierId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':supplierId', $supplierId, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'No supplier ID provided']);
}
?>