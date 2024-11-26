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
        SELECT DATE_FORMAT(OrderTime, '%Y-%m') AS month, 
               SUM(TotalPrice) AS revenue 
        FROM orders 
        WHERE Status = 'paid'
        GROUP BY month
        ORDER BY month ASC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Lấy doanh thu theo loại vải
    $fabric_revenue = $conn->query("
        SELECT category.Name AS fabric_name, 
               SUM(bolt.Length * category.Price) AS revenue 
        FROM bolt 
        JOIN category ON bolt.CCode = category.CCode
        GROUP BY fabric_name
        ORDER BY revenue DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Lấy doanh thu theo khách hàng
    $customer_revenue = $conn->query("
        SELECT CONCAT(customer.Fname, ' ', customer.Lname) AS customer_name, 
               SUM(orders.TotalPrice) AS revenue 
        FROM orders 
        JOIN customer ON orders.CusId = customer.CusId
        WHERE orders.Status = 'paid'
        GROUP BY customer_name
        ORDER BY revenue DESC
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Đặt tiêu đề cho JSON và trả về dữ liệu
    header('Content-Type: application/json');
    echo json_encode([
        'monthly_revenue' => $monthly_revenue,
        'fabric_revenue' => $fabric_revenue,
        'customer_revenue' => $customer_revenue,
        'has_data' => !empty($monthly_revenue) || !empty($fabric_revenue) || !empty($customer_revenue)
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
