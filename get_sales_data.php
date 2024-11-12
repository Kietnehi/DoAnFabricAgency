<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php'; // Kết nối đến cơ sở dữ liệu

try {
    // Lấy doanh thu theo tháng
    $monthly_revenue = $conn->query("
        SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, SUM(total_amount) AS revenue 
        FROM Orders 
        GROUP BY month
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Lấy doanh thu theo loại vải
    $fabric_revenue = $conn->query("
        SELECT Fabric_Types.name AS fabric_name, SUM(Fabric_Rolls.length * Fabric_Types.current_price) AS revenue 
        FROM Order_Fabric_Rolls 
        JOIN Fabric_Rolls ON Order_Fabric_Rolls.roll_id = Fabric_Rolls.roll_id
        JOIN Fabric_Types ON Fabric_Rolls.fabric_type_id = Fabric_Types.fabric_type_id 
        GROUP BY fabric_name
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Lấy doanh thu theo khách hàng
    $customer_revenue = $conn->query("
        SELECT CONCAT(Customers.first_name, ' ', Customers.last_name) AS customer_name, SUM(total_amount) AS revenue 
        FROM Orders 
        JOIN Customers ON Orders.customer_id = Customers.customer_id 
        GROUP BY customer_name
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Đặt tiêu đề cho JSON và trả về dữ liệu
    header('Content-Type: application/json');
    echo json_encode([
        'monthly_revenue' => $monthly_revenue,
        'fabric_revenue' => $fabric_revenue,
        'customer_revenue' => $customer_revenue
    ]);

} catch (PDOException $e) {
    // Xử lý lỗi truy vấn và trả về lỗi JSON
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Database query failed: ' . $e->getMessage()
    ]);
    exit();
}
?>
