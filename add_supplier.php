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
    $name = $_POST['name'];
    $address = $_POST['address'];
    $bank_account = $_POST['bank_account'];
    $tax_code = $_POST['tax_code'];
    $phone = $_POST['phone'];
    $ecode = $_POST['ecode']; // Mã nhân viên quản lý nhà cung cấp

    try {
        // Thêm thông tin nhà cung cấp mới vào cơ sở dữ liệu
        $stmt = $conn->prepare("INSERT INTO supplier (Name, Address, BankAccount, TaxCode, Phone, ECode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $address, $bank_account, $tax_code, $phone, $ecode]);

        // Ghi thông báo thành công vào session
        $_SESSION['success_message'] = "Thêm nhà cung cấp mới thành công!";
        
        // Chuyển hướng về chính trang thêm nhà cung cấp để hiển thị thông báo
        header("Location: add_supplier.php");
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
    <title>Thêm Nhà Cung Cấp</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .alert-success {
            margin-top: 20px;
            transition: opacity 0.5s ease-out;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Lấy phần tử thông báo
            const alertBox = document.querySelector('.alert-success');

            // Nếu thông báo tồn tại, đặt timeout để ẩn nó sau 3 giây
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.opacity = '0'; // Giảm opacity để tạo hiệu ứng
                    setTimeout(() => alertBox.remove(), 500); // Xóa phần tử sau khi ẩn
                }, 3000); // 3 giây
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <h2>Thêm Nhà Cung Cấp Mới</h2>

        <?php
        // Hiển thị thông báo thành công nếu có
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success'>{$_SESSION['success_message']}</div>";
            // Xóa thông báo sau khi hiển thị
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="add_supplier.php" method="POST">
            <div class="form-group">
                <label for="name">Tên Nhà Cung Cấp:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="address">Địa Chỉ:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="bank_account">Tài Khoản Ngân Hàng:</label>
                <input type="text" class="form-control" id="bank_account" name="bank_account" required>
            </div>
            <div class="form-group">
                <label for="tax_code">Mã Số Thuế:</label>
                <input type="text" class="form-control" id="tax_code" name="tax_code" required>
            </div>
            <div class="form-group">
                <label for="phone">Số Điện Thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="ecode">Nhân Viên Đối Tác:</label>
                <select class="form-control" id="ecode" name="ecode" required>
                    <?php
                    // Lấy danh sách nhân viên từ bảng `employee`
                    $stmt = $conn->query("SELECT ECode, CONCAT(Fname, ' ', Lname) AS FullName FROM employee WHERE Role='PartnerStaff'");
                    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($employees as $employee) {
                        echo "<option value='{$employee['ECode']}'>{$employee['FullName']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Thêm Nhà Cung Cấp</button>
        </form>
    </div>
</body>
</html>
