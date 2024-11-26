<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy `order_id` từ URL
$order_id = $_GET['id'];

// Lấy thông tin đơn hàng hiện tại từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM orders WHERE OCode = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra nếu trạng thái là "paid"
if ($order['Status'] === 'paid') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('alertModal');
            modal.style.display = 'flex';
            setTimeout(() => { window.location.href = 'orders.php'; }, 2000);
        });
    </script>";
}

// Lấy danh sách khách hàng và nhân viên cho dropdown
$customers = $conn->query("SELECT CusId, Fname, Lname FROM customer")->fetchAll(PDO::FETCH_ASSOC);
$employees = $conn->query("SELECT ECode, Fname, Lname FROM employee")->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số tiền đã thanh toán cho đơn hàng này
$paid_stmt = $conn->prepare("SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE OCode = ?");
$paid_stmt->execute([$order_id]);
$total_paid = $paid_stmt->fetchColumn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $order_date = $_POST['order_date'];
    $total_price = $_POST['total_price'];
    $status = $_POST['status'];
    $cancellation_reason = $_POST['cancellation_reason'] ?? null;

    // Kiểm tra nếu trạng thái là "paid" và tổng số tiền chưa được thanh toán đủ
    if ($status === 'paid' && $total_paid < $total_price) {
        echo "<script>alert('Bạn phải thanh toán đủ số tiền mới có thể sửa trạng thái thành Paid.');</script>";
    } elseif ($status === 'cancelled' && empty($cancellation_reason)) {
        echo "<script>alert('Vui lòng nhập lý do khi hủy đơn hàng.');</script>";
    } else {
        // Cập nhật thông tin đơn hàng
        $sql = "UPDATE orders SET CusId = ?, ECode = ?, OrderTime = ?, TotalPrice = ?, Status = ?, Cancellation_Reason = ? WHERE OCode = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id, $employee_id, $order_date, $total_price, $status, $cancellation_reason, $order_id]);

        // Quay lại trang danh sách đơn hàng
        header("Location: orders.php");
        exit();
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Đơn Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            font-weight: bold;
        }

        input, select, textarea, button {
            margin-top: 5px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* Modal alert CSS */
        #alertModal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        #alertModalContent {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        #alertModalContent h3 {
            color: #333;
            margin: 0;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    <script>
        function toggleCancellationReason(status) {
            const reasonField = document.getElementById('cancellation_reason_field');
            if (status === 'cancelled') {
                reasonField.style.display = 'block';
            } else {
                reasonField.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <!-- Alert Modal -->
    <div id="alertModal">
        <div id="alertModalContent">
            <h3>Đơn hàng đã hoàn thành nên không được sửa.</h3>
        </div>
    </div>

    <div class="container">
        <h1>Sửa Đơn Hàng</h1>
        <form action="edit_order.php?id=<?= $order_id ?>" method="POST">
            <label for="customer_id">Khách Hàng:</label>
            <select name="customer_id" required>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer['CusId']; ?>" <?= $customer['CusId'] == $order['CusId'] ? 'selected' : '' ?>>
                        <?= $customer['Fname'] . " " . $customer['Lname']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="employee_id">Nhân Viên:</label>
            <select name="employee_id" required>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['ECode']; ?>" <?= $employee['ECode'] == $order['ECode'] ? 'selected' : '' ?>>
                        <?= $employee['Fname'] . " " . $employee['Lname']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="order_date">Ngày Đặt Hàng:</label>
            <input type="datetime-local" name="order_date" value="<?= date('Y-m-d\TH:i', strtotime($order['OrderTime'])); ?>" required>

            <label for="total_price">Tổng Tiền:</label>
            <input type="number" name="total_price" step="0.01" value="<?= $order['TotalPrice']; ?>" required>

            <label for="status">Trạng Thái:</label>
            <select name="status" required onchange="toggleCancellationReason(this.value)">
                <option value="new" <?= $order['Status'] == 'new' ? 'selected' : '' ?>>New</option>
                <option value="ordered" <?= $order['Status'] == 'ordered' ? 'selected' : '' ?>>Ordered</option>
                <option value="partial_payment" <?= $order['Status'] == 'partial_payment' ? 'selected' : '' ?>>Partial Payment</option>
                <option value="paid" <?= $order['Status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="cancelled" <?= $order['Status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>

            <div id="cancellation_reason_field" style="display: <?= $order['Status'] === 'cancelled' ? 'block' : 'none'; ?>;">
                <label for="cancellation_reason">Lý Do Hủy Đơn:</label>
                <textarea name="cancellation_reason" id="cancellation_reason" rows="4"><?= htmlspecialchars($order['Cancellation_Reason']); ?></textarea>
            </div>

            <button type="submit">Lưu Thay Đổi</button>
        </form>
        <a href="orders.php" class="back-link" style="color: #fff;">Quay lại Danh Sách Đơn Hàng</a>
    </div>
</body>
</html>