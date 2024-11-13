<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Thanh điều hướng

// Kiểm tra nếu form đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    // Thêm thông tin nhân viên mới vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO employees (first_name, last_name, gender, address, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $gender, $address, $phone, $role]);

    header("Location: employees.php"); // Chuyển hướng về trang danh sách nhân viên
    exit();
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Nhân Viên</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Thêm Nhân Viên Mới</h2>
        <form action="add_employee.php" method="POST">
            <div class="form-group">
                <label for="first_name">Tên:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Họ:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="gender">Giới Tính:</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="address">Địa Chỉ:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Số Điện Thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="role">Chức Vụ:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="Manager">Quản lý</option>
                    <option value="Partner">Đối tác</option>
                    <option value="Operations">Hoạt động</option>
                    <option value="Office">Văn phòng</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Nhân Viên</button>
        </form>
    </div>
</body>
</html>
