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
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    try {
        // Thêm thông tin nhân viên mới vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO employee (Fname, Lname, Gender, Address, Phone, Role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fname, $lname, $gender, $address, $phone, $role]);

        header("Location: employees.php"); // Chuyển hướng về trang danh sách nhân viên
        exit();
    } catch (PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }
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
                <label for="fname">Tên:</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
            </div>
            <div class="form-group">
                <label for="lname">Họ:</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
            </div>
            <div class="form-group">
                <label for="gender">Giới Tính:</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="Male">Nam</option>
                    <option value="Female">Nữ</option>
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
                    <option value="PartnerStaff">Đối tác</option>
                    <option value="OperationalStaff">Hoạt động</option>
                    <option value="OfficeStaff">Văn phòng</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Nhân Viên</button>
        </form>
    </div>
</body>
</html>
