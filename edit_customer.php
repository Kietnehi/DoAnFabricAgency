<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php';

// Lấy `CusId` từ URL
$cus_id = $_GET['id'];

// Lấy thông tin khách hàng hiện tại từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT c.*, cs.Alert, cs.BadDebt, cs.AlertStartDate 
                        FROM customer c 
                        LEFT JOIN customerstatus cs ON c.CusId = cs.CusId 
                        WHERE c.CusId = ?");
$stmt->execute([$cus_id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu cảnh báo đã tồn tại hơn 6 tháng
$is_eligible_bad_debt = false;
if ($customer['Alert'] && $customer['AlertStartDate']) {
    $alert_date = strtotime($customer['AlertStartDate']);
    $six_months_ago = strtotime('-6 months');
    $is_eligible_bad_debt = ($alert_date <= $six_months_ago);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $dept = $_POST['dept'];
    $ecode = $_POST['ecode'];
    $alert = isset($_POST['alert']) ? 1 : 0;
    $bad_debt = isset($_POST['bad_debt']) ? 1 : 0;

    try {
        // Cập nhật thông tin khách hàng
        $sql = "UPDATE customer SET Fname = ?, Lname = ?, Phone = ?, Dept = ?, ECode = ? WHERE CusId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fname, $lname, $phone, $dept, $ecode, $cus_id]);

        // Cập nhật hoặc chèn trạng thái cảnh báo và nợ xấu
        if ($alert && !$customer['Alert']) {
            $alert_start_date = date('Y-m-d'); // Nếu mới bật cảnh báo, đặt ngày bắt đầu cảnh báo
        } else {
            $alert_start_date = $customer['AlertStartDate']; // Giữ nguyên ngày bắt đầu cảnh báo
        }

        $status_stmt = $conn->prepare("INSERT INTO customerstatus (CusId, Alert, BadDebt, AlertStartDate) 
                                       VALUES (?, ?, ?, ?) 
                                       ON DUPLICATE KEY UPDATE Alert = VALUES(Alert), BadDebt = VALUES(BadDebt), AlertStartDate = VALUES(AlertStartDate)");
        $status_stmt->execute([$cus_id, $alert, $is_eligible_bad_debt && $bad_debt ? 1 : 0, $alert_start_date]);

        // Quay lại trang danh sách khách hàng
        header("Location: customers.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

ob_end_flush();
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
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            outline: none;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .checkbox-group label {
            display: inline-block;
            margin-left: 5px;
            font-weight: normal;
            color: #555;
        }
        input[type="checkbox"] {
            width: auto;
            margin: 0;
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
        .warning-text {
            color: red;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sửa Thông Tin Khách Hàng</h1>
        <form action="edit_customer.php?id=<?= $cus_id ?>" method="POST">
            <label for="fname">Tên:</label>
            <input type="text" name="fname" value="<?= htmlspecialchars($customer['Fname']) ?>" required>

            <label for="lname">Họ:</label>
            <input type="text" name="lname" value="<?= htmlspecialchars($customer['Lname']) ?>" required>

            <label for="phone">Số Điện Thoại:</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($customer['Phone']) ?>" required>

            <label for="dept">Công Nợ:</label>
            <input type="number" name="dept" step="0.01" value="<?= htmlspecialchars($customer['Dept']) ?>" required>

            <label for="ecode">Nhân Viên Phụ Trách:</label>
            <select name="ecode" required>
                <?php
                // Lấy danh sách nhân viên
                $stmt = $conn->prepare("SELECT ECode, CONCAT(Fname, ' ', Lname) AS FullName FROM employee");
                $stmt->execute();
                $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($employees as $employee):
                    $selected = ($employee['ECode'] == $customer['ECode']) ? 'selected' : '';
                ?>
                    <option value="<?= $employee['ECode'] ?>" <?= $selected ?>>
                        NV <?= htmlspecialchars($employee['ECode']) ?> - <?= htmlspecialchars($employee['FullName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <div class="checkbox-group">
                <input 
                    type="checkbox" 
                    id="alert" 
                    name="alert" 
                    <?= $customer['Alert'] ? 'checked' : '' ?> 
                    <?= $customer['Dept'] <= 2000 ? 'disabled' : '' ?>>
                <label for="alert">Trạng Thái Cảnh Báo</label>
            </div>
            <?php if ($customer['Dept'] <= 2000): ?>
                <small class="warning-text">Công nợ phải lớn hơn 2000 để bật cảnh báo.</small>
            <?php endif; ?>

            <div class="checkbox-group">
                <input 
                    type="checkbox" 
                    id="bad_debt" 
                    name="bad_debt" 
                    <?= $customer['BadDebt'] ? 'checked' : '' ?> 
                    <?= (!$is_eligible_bad_debt) ? 'disabled' : '' ?>>
                <label for="bad_debt">Nợ Xấu</label>
            </div>
            <?php if (!$is_eligible_bad_debt): ?>
                <small class="warning-text">Chỉ được đánh dấu nợ xấu khi cảnh báo đã tồn tại hơn 6 tháng.</small>
            <?php endif; ?>

            <button type="submit">Lưu Thay Đổi</button>
        </form>
        <a href="customers.php">Quay lại Danh Sách Khách Hàng</a>
    </div>
</body>
</html>
