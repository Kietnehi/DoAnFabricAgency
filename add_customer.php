<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Truy vấn danh sách nhân viên từ bảng `employee`
$employees = [];
try {
    $stmt = $conn->prepare("SELECT ECode, CONCAT(Fname, ' ', Lname) AS FullName FROM employee WHERE Role = 'OfficeStaff'");
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: Không thể lấy danh sách nhân viên. " . $e->getMessage();
    exit();
}

// Kiểm tra nếu form đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname']; // Tên
    $lname = $_POST['lname']; // Họ
    $address = $_POST['address']; // Địa chỉ
    $phone = $_POST['phone']; // Số điện thoại
    $dept = $_POST['dept']; // Công nợ
    $ecode = $_POST['ecode']; // Mã nhân viên chăm sóc khách hàng (ECode)
    //$alert = isset($_POST['alert']) ? 1 : 0; // Trạng thái cảnh báo
    //$bad_debt = isset($_POST['bad_debt']) ? 1 : 0; // Nợ xấu

    try {
        // Kiểm tra xem ECode có tồn tại không
        $stmt = $conn->prepare("SELECT COUNT(*) FROM employee WHERE ECode = ?");
        $stmt->execute([$ecode]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("Mã nhân viên không tồn tại.");
        }

        // Thêm thông tin khách hàng vào bảng `customer`
        $stmt = $conn->prepare("INSERT INTO customer (Fname, Lname, Phone, Address, Dept, ECode) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fname, $lname, $phone, $address, $dept, $ecode]);

        // Lấy ID khách hàng vừa thêm
        $cus_id = $conn->lastInsertId();

        // Thêm hoặc cập nhật trạng thái cảnh báo và nợ xấu
        //$stmt = $conn->prepare("INSERT INTO customerstatus (CusId, Alert, BadDebt) VALUES (?, ?, ?)");
        //$stmt->execute([$cus_id, $alert, $bad_debt]);

        // Chuyển hướng về trang danh sách khách hàng
        header("Location: customers.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: Không thể thêm khách hàng. " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Khách Hàng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .checkbox-group input[type="checkbox"] {
            margin-right: 10px;
        }
        .checkbox-group small {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Thêm Khách Hàng Mới</h2>
        <form action="add_customer.php" method="POST">
            <div class="form-group">
                <label for="fname">Tên:</label>
                <input type="text" class="form-control" id="fname" name="fname" required>
            </div>
            <div class="form-group">
                <label for="lname">Họ:</label>
                <input type="text" class="form-control" id="lname" name="lname" required>
            </div>
            <div class="form-group">
                <label for="address">Địa Chỉ:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Số Điện Thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <!-- <div class="form-group">
                <label for="dept">Công Nợ (USD):</label>
                <input type="number" class="form-control" id="dept" name="dept" step="0.01" required>
            </div> -->
            <div class="form-group">
                <label for="ecode">Nhân Viên Chăm Sóc:</label>
                <select class="form-control" id="ecode" name="ecode" required>
                    <option value="">Chọn nhân viên</option>
                    <?php foreach ($employees as $employee): ?>
                        <option value="<?= htmlspecialchars($employee['ECode']) ?>">
                            <?= htmlspecialchars($employee['FullName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- <div class="checkbox-group">
                <input type="checkbox" id="alert" name="alert" value="1" disabled>
                <label for="alert">Trạng Thái Cảnh Báo</label>
                <small id="alert-warning" class="alert-warning"></small>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="bad_debt" name="bad_debt" value="1" disabled>
                <label for="bad_debt">Nợ Xấu</label>
                <small id="bad-debt-warning" class="bad-debt-warning"></small>
            </div> -->
            <button type="submit" class="btn btn-primary">Thêm Khách Hàng</button>
        </form>
    </div>

    <script>
        const deptInput = document.getElementById('dept');
        const alertCheckbox = document.getElementById('alert');
        const badDebtCheckbox = document.getElementById('bad_debt');
        const alertWarning = document.getElementById('alert-warning');
        const badDebtWarning = document.getElementById('bad-debt-warning');

        deptInput.addEventListener('input', () => {
            const deptValue = parseFloat(deptInput.value) || 0;

            // Enable/disable cảnh báo
            if (deptValue > 2000) {
                alertCheckbox.disabled = false;
                alertWarning.textContent = '';
            } else {
                alertCheckbox.disabled = true;
                alertCheckbox.checked = false;
                alertWarning.textContent = 'Công nợ phải lớn hơn 2000 để bật cảnh báo.';
            }

            // Enable/disable nợ xấu (giả sử nợ kéo dài hơn 6 tháng - cần logic thêm ở backend để kiểm tra)
            if (deptValue > 2000) {
                badDebtCheckbox.disabled = false;
                badDebtWarning.textContent = '';
            } else {
                badDebtCheckbox.disabled = true;
                badDebtCheckbox.checked = false;
                badDebtWarning.textContent = 'Công nợ phải lớn hơn 2000 và kéo dài hơn 6 tháng để đánh dấu nợ xấu.';
            }
        });
    </script>
</body>
</html>
