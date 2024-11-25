<?php
require 'connect.php';

if (isset($_GET['id'])) {
    $supplierId = $_GET['id'];

    $sql = "
        SELECT c.CCode, c.Name AS ProductName, c.Color, c.Price, c.AppliedDate, c.RemainQuantity, c.img 
        FROM category c 
        WHERE c.SCode = :supplierId
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':supplierId', $supplierId, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($products) {
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Mã sản phẩm</th><th>Tên sản phẩm</th><th>Màu sắc</th><th>Giá</th><th>Ngày áp dụng</th><th>Số lượng</th><th>Hình ảnh</th></tr></thead>';
        echo '<tbody>';
        foreach ($products as $product) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($product['CCode']) . '</td>';
            echo '<td>' . htmlspecialchars($product['ProductName']) . '</td>';
            echo '<td>' . htmlspecialchars($product['Color']) . '</td>';
            echo '<td>' . htmlspecialchars($product['Price']) . '</td>';
            echo '<td>' . htmlspecialchars($product['AppliedDate']) . '</td>';
            echo '<td>' . htmlspecialchars($product['RemainQuantity']) . '</td>';
            echo '<td>';
            if ($product['img']) {
                echo '<img src="img/' . htmlspecialchars($product['img']) . '" alt="Hình ảnh sản phẩm" width="50">';
            } else {
                echo '<p class="no-data">Chưa có hình ảnh</p>';
            }
            echo '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>Không có sản phẩm nào.</p>';
    }
} else {
    echo '<p>ID nhà cung cấp không hợp lệ.</p>';
}

$conn = null;
?>