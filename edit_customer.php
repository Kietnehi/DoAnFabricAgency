<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require 'connect.php'; // Kết nối cơ sở dữ liệu
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy `customer_id` từ URL
$customer_id = $_GET['id'];

// Lấy thông tin khách hàng hiện tại từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM Customers WHERE customer_id = ?");
$stmt->execute([$customer_id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $outstanding_balance = $_POST['outstanding_balance'];
    $warning_status = isset($_POST['warning_status']) ? 1 : 0;

    // Cập nhật thông tin khách hàng
    $sql = "UPDATE Customers SET first_name = ?, last_name = ?, phone = ?, outstanding_balance = ?, warning_status = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$first_name, $last_name, $phone, $outstanding_balance, $warning_status, $customer_id]);

    // Cập nhật trạng thái cảnh báo và nợ xấu dựa trên công nợ
    if ($outstanding_balance > 2000) {
        $warning_status = 1;
        $warning_start_date = $customer['warning_start_date'] ?: date('Y-m-d'); // Đặt ngày cảnh báo nếu chưa có
        $stmt = $conn->prepare("UPDATE Customers SET warning_status = 1, warning_start_date = ? WHERE customer_id = ?");
        $stmt->execute([$warning_start_date, $customer_id]);
    } else {
        $stmt = $conn->prepare("UPDATE Customers SET warning_status = 0, warning_start_date = NULL, bad_debt_status = 0 WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
    }

    // Kiểm tra nếu cảnh báo đã kéo dài hơn 6 tháng để đánh dấu là nợ xấu
    if ($customer['warning_status'] && $customer['warning_start_date'] && (strtotime($customer['warning_start_date']) <= strtotime('-6 months'))) {
        $stmt = $conn->prepare("UPDATE Customers SET bad_debt_status = 1 WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
    }

    // Quay lại trang danh sách khách hàng
    header("Location: customers.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Khách Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            color: #555;
            margin-top: 10px;
            display: block;
        }
        input[type="text"], input[type="number"], input[type="checkbox"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }
        button {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sửa Thông Tin Khách Hàng</h1>
        <form action="edit_customer.php?id=<?= $customer_id ?>" method="POST">
            <label for="first_name">Tên:</label>
            <input type="text" name="first_name" value="<?= htmlspecialchars($customer['first_name']) ?>" required>

            <label for="last_name">Họ:</label>
            <input type="text" name="last_name" value="<?= htmlspecialchars($customer['last_name']) ?>" required>

            <label for="phone">Số Điện Thoại:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($customer['phone']) ?>" required>

            <label for="outstanding_balance">Công Nợ:</label>
            <input type="number" name="outstanding_balance" step="0.01" value="<?= htmlspecialchars($customer['outstanding_balance']) ?>" required>

            <label for="warning_status">Trạng Thái Cảnh Báo:</label>
            <input type="checkbox" name="warning_status" <?= $customer['warning_status'] ? 'checked' : '' ?>>

            <button type="submit">Lưu Thay Đổi</button>
        </form>
        <a href="customers.php">Quay lại Danh Sách Khách Hàng</a>
    </div>
</body>
</html>
