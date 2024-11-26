<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Xử lý thanh toán đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_order_id'])) {
    $order_id = $_POST['pay_order_id'];
    $payment_amount = floatval($_POST['payment_amount']);

    // Lấy tổng tiền đơn hàng và thông tin khách hàng
    $order_stmt = $conn->prepare("SELECT TotalPrice, CusId, Status FROM orders WHERE OCode = :order_id");
    $order_stmt->execute(['order_id' => $order_id]);
    $order = $order_stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        if ($order['Status'] === 'cancelled') {
            echo "<script>alert('Đơn hàng đã bị hủy. Không thể thanh toán cho đơn hàng này.'); window.location.href = 'orders.php';</script>";
            exit();
        }

        $total_price = $order['TotalPrice'];
        $cus_id = $order['CusId'];

        // Lấy tổng tiền đã thanh toán cho đơn hàng này
        $paid_stmt = $conn->prepare("SELECT COALESCE(SUM(Amount), 0) AS TotalPaid FROM customer_partialpayments WHERE OCode = :order_id");
        $paid_stmt->execute(['order_id' => $order_id]);
        $total_paid = $paid_stmt->fetchColumn();

        $remaining_balance = $total_price - $total_paid;

        if ($payment_amount > $remaining_balance) {
            echo "<script>alert('Số tiền thanh toán vượt quá số dư. Vui lòng thử lại.');</script>";
        } else {
            // Lưu lịch sử thanh toán vào bảng customer_partialpayments
            $stmt = $conn->prepare("INSERT INTO customer_partialpayments (CusId, OCode, PaymentTime, Amount) VALUES (:cus_id, :order_id, NOW(), :amount)");
            $stmt->execute(['cus_id' => $cus_id, 'order_id' => $order_id, 'amount' => $payment_amount]);

            // Cập nhật công nợ của khách hàng
            $update_sql = "UPDATE customer SET Dept = Dept - :amount WHERE CusId = :cus_id";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute(['amount' => $payment_amount, 'cus_id' => $cus_id]);

            // Tính tổng số tiền đã thanh toán sau khi cập nhật
            $new_total_paid = $total_paid + $payment_amount;

            // Cập nhật trạng thái đơn hàng
            $order_update_stmt = $conn->prepare("
                UPDATE orders 
                SET Status = CASE 
                    WHEN (SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE OCode = :order_id) >= TotalPrice 
                    THEN 'paid' 
                    ELSE 'partial_payment' 
                END
                WHERE OCode = :order_id
            ");
            $order_update_stmt->execute(['order_id' => $order_id]);

            echo "<script>alert('Thanh toán thành công. Lịch sử đã được lưu.'); window.location.href = 'orders.php';</script>";
        }
    } else {
        echo "<script>alert('Đơn hàng không tồn tại.');</script>";
    }
}

// Xử lý xóa đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $order_id = $_POST['delete_order_id'];

    // Kiểm tra xem đơn hàng có tồn tại không
    $check_stmt = $conn->prepare("SELECT OCode, Status, (TotalPrice - (SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE OCode = orders.OCode)) AS RemainingBalance FROM orders WHERE OCode = :order_id");
    $check_stmt->execute(['order_id' => $order_id]);
    $order = $check_stmt->fetch(PDO::FETCH_ASSOC);

    if ($order && $order['RemainingBalance'] == 0 && $order['Status'] == 'paid') {
        // Xóa đơn hàng khỏi cơ sở dữ liệu
        $delete_stmt = $conn->prepare("DELETE FROM orders WHERE OCode = :order_id");
        $delete_stmt->execute(['order_id' => $order_id]);

        // Xóa tất cả các khoản thanh toán liên quan đến đơn hàng
        $delete_payments_stmt = $conn->prepare("DELETE FROM customer_partialpayments WHERE OCode = :order_id");
        $delete_payments_stmt->execute(['order_id' => $order_id]);

        // Thông báo thành công và chuyển hướng về trang quản lý đơn hàng
        echo "<script>alert('Đơn hàng đã được xóa thành công.'); window.location.href = 'orders.php';</script>";
    } else {
        echo "<script>alert('Đơn hàng không thể xóa. Chỉ có thể xóa đơn hàng đã thanh toán đủ và có trạng thái hoàn thành.');</script>";
    }
}

// Xử lý tìm kiếm và sắp xếp
$query = isset($_GET['query']) ? $_GET['query'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'OCode';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT orders.*, 
               customer.Fname AS customer_fname, customer.Lname AS customer_lname, 
               employee.Fname AS emp_fname, employee.Lname AS emp_lname,
               GREATEST(orders.TotalPrice - (SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE OCode = orders.OCode), 0) AS RemainingBalance
        FROM orders
        JOIN customer ON orders.CusId = customer.CusId
        JOIN employee ON orders.ECode = employee.ECode
        WHERE (customer.Fname LIKE :query OR customer.Lname LIKE :query) AND employee.Role = 'OperationalStaff'
        ORDER BY $order_by $order_dir
        LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_stmt = $conn->prepare("SELECT COUNT(*) FROM orders JOIN customer ON orders.CusId = customer.CusId WHERE customer.Fname LIKE :query OR customer.Lname LIKE :query");
$total_stmt->execute([':query' => "%$query%"]);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$new_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="orders.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            max-width: 500px;
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .pay-btn {
            background: linear-gradient(to right, #f39c12, #f1c40f);
            color: white;
            border: none;
            padding: 10px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 2px 1px;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s ease, transform 0.2s;
        }

        .pay-btn:hover {
            background: linear-gradient(to right, #d68910, #d4ac0d);
            transform: scale(1.05);
        }
    </style>
    <script>
        function openPaymentModal(orderId, remainingBalance) {
            document.getElementById('pay_order_id').value = orderId;
            document.getElementById('remaining_balance').innerText = remainingBalance.toFixed(2);
            document.getElementById('paymentModal').style.display = 'block';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        function openDeleteModal(orderId) {
            document.getElementById('delete_order_id').value = orderId;
            document.getElementById('deleteOrderModal').style.display = 'block';
        }

        function closeDeleteModal() {
            document.getElementById('deleteOrderModal').style.display = 'none';
        }

        function submitPaymentForm() {
            var paymentAmount = parseFloat(document.getElementById('payment_amount').value);
            if (isNaN(paymentAmount) || paymentAmount <= 0) {
                alert('Số tiền thanh toán phải lớn hơn 0.');
                return false;
            }
            document.getElementById('payment_form').submit();
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Danh Sách Đơn Hàng</h1>

        <form method="GET" action="orders.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm kiếm theo tên khách hàng..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <form method="POST" id="payment_form">
            <input type="hidden" name="pay_order_id" id="pay_order_id">
            <input type="hidden" name="payment_amount" id="payment_amount">
        </form>

        <form method="POST" id="deleteOrderForm">
            <input type="hidden" name="delete_order_id" id="delete_order_id">
        </form>

        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=OCode&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=customer_fname&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Khách Hàng</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=emp_fname&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Nhân Viên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=OrderTime&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Ngày Đặt</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=TotalPrice&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Tổng Tiền</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=RemainingBalance&order_dir=<?= $new_order_dir ?>"> <i class="fas fa-sort"></i>Số Tiền Chưa Thanh Toán</th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Status&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Trạng Thái</a></th>
                <th>Hành động</th>
            </tr>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['OCode']; ?></td>
                        <td><?= htmlspecialchars($order['customer_fname'] . " " . $order['customer_lname']); ?></td>
                        <td><?= htmlspecialchars($order['emp_fname'] . " " . $order['emp_lname']); ?></td>
                        <td><?= htmlspecialchars($order['OrderTime']); ?></td>
                        <td><?= htmlspecialchars(number_format($order['TotalPrice'], 2)); ?></td>
                        <td><?= htmlspecialchars(number_format($order['RemainingBalance'], 2)); ?></td>
                        <td>
                            <?= $order['Status'] === 'cancelled'
                                ? '<span class="status-cancelled">Đã Hủy</span><br>Lý Do: ' . htmlspecialchars($order['Cancellation_Reason'])
                                : htmlspecialchars($order['Status']);
                            ?>
                        </td>
                        <td class="action-buttons">
                            <a href="edit_order.php?id=<?= $order['OCode']; ?>" class="edit-btn">Sửa</a>
                            <button type="button" onclick="openDeleteModal(<?= $order['OCode']; ?>)" class="delete-btn">Xóa</button>
                            <?php if ($order['Status'] !== 'paid' && $order['Status'] !== 'cancelled'): ?>
                                <button type="button" onclick="openPaymentModal(<?= $order['OCode']; ?>, <?= $order['RemainingBalance']; ?>)" class="pay-btn">Thanh toán</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Không tìm thấy đơn hàng nào.</td>
                </tr>
            <?php endif; ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePaymentModal()">&times;</span>
            <h2>Thanh Toán Đơn Hàng</h2>
            <form id="paymentForm" method="POST" onsubmit="return submitPaymentForm();">
                <input type="hidden" name="pay_order_id" id="pay_order_id">
                <label for="payment_amount">Số Tiền Thanh Toán:</label>
                <input type="number" name="payment_amount" id="payment_amount" step="0.01" required>
                <p>Số tiền tối đa: $<span id="remaining_balance"></span></p>
                <button type="submit">Xác Nhận</button>
            </form>
        </div>
    </div>

    <div id="deleteOrderModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
            <h2>Xóa Đơn Hàng</h2>
            <form id="deleteOrderForm" method="POST">
                <input type="hidden" name="delete_order_id" id="delete_order_id">
                <label for="cancellation_reason">Lý Do Hủy Đơn:</label>
                <textarea name="cancellation_reason" id="cancellation_reason" rows="5" cols="55"></textarea>
                <button type="submit">Xác Nhận</button>
            </form>
        </div>
    </div>
</body>

</html>