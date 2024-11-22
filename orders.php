<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Xử lý xóa đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $order_id = $_POST['delete_order_id'];

    // Xóa chi tiết đơn hàng
    $conn->prepare("DELETE FROM order_detail WHERE OCode = :order_id")->execute(['order_id' => $order_id]);

    // Xóa đơn hàng
    $conn->prepare("DELETE FROM orders WHERE OCode = :order_id")->execute(['order_id' => $order_id]);
}

// Xử lý thanh toán đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_order_id'])) {
    $order_id = $_POST['pay_order_id'];
    $payment_amount = floatval($_POST['payment_amount']);

    // Lấy tổng tiền đơn hàng và số tiền đã thanh toán
    $order_stmt = $conn->prepare("SELECT TotalPrice FROM orders WHERE OCode = :order_id");
    $order_stmt->execute(['order_id' => $order_id]);
    $total_price = $order_stmt->fetchColumn();

    $paid_stmt = $conn->prepare("SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE CusId = (SELECT CusId FROM orders WHERE OCode = :order_id)");
    $paid_stmt->execute(['order_id' => $order_id]);
    $total_paid = $paid_stmt->fetchColumn();

    $remaining_balance = $total_price - $total_paid;

    if ($payment_amount > $remaining_balance) {
        echo "<script>alert('Số tiền thanh toán vượt quá số dư. Vui lòng thử lại.');</script>";
    } else {
        $cus_id_stmt = $conn->prepare("SELECT CusId FROM orders WHERE OCode = :order_id");
        $cus_id_stmt->execute(['order_id' => $order_id]);
        $cus_id = $cus_id_stmt->fetchColumn();

        $stmt = $conn->prepare("INSERT INTO customer_partialpayments (CusId, PaymentTime, Amount) VALUES (:cus_id, NOW(), :amount)");
        $stmt->execute(['cus_id' => $cus_id, 'amount' => $payment_amount]);

        $new_status = ($payment_amount == $remaining_balance) ? 'paid' : 'partial_payment';
        $status_stmt = $conn->prepare("UPDATE orders SET Status = :status WHERE OCode = :order_id");
        $status_stmt->execute(['status' => $new_status, 'order_id' => $order_id]);
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
               employee.Fname AS emp_fname, employee.Lname AS emp_lname 
        FROM orders
        JOIN customer ON orders.CusId = customer.CusId
        JOIN employee ON orders.ECode = employee.ECode
        WHERE customer.Fname LIKE :query OR customer.Lname LIKE :query
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
    <script>
        function openPaymentModal(orderId, remainingBalance) {
            const amount = prompt(`Nhập số tiền thanh toán (tối đa $${remainingBalance.toFixed(2)}):`);
            if (amount && parseFloat(amount) > 0 && parseFloat(amount) <= remainingBalance) {
                document.getElementById('pay_order_id').value = orderId;
                document.getElementById('payment_amount').value = parseFloat(amount);
                document.getElementById('payment_form').submit();
            } else {
                alert('Vui lòng nhập số tiền hợp lệ.');
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Danh Sách Đơn Hàng</h1>

        <form method="GET" action="orders.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm kiếm theo tên khách hàng..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit">Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <form method="POST" id="payment_form">
            <input type="hidden" name="pay_order_id" id="pay_order_id">
            <input type="hidden" name="payment_amount" id="payment_amount">
        </form>

        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=OCode&order_dir=<?= $new_order_dir ?>">ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=customer_fname&order_dir=<?= $new_order_dir ?>">Khách Hàng</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=emp_fname&order_dir=<?= $new_order_dir ?>">Nhân Viên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=OrderTime&order_dir=<?= $new_order_dir ?>">Ngày Đặt</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=TotalPrice&order_dir=<?= $new_order_dir ?>">Tổng Tiền</a></th>
                <th>Số Tiền Chưa Thanh Toán</th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Status&order_dir=<?= $new_order_dir ?>">Trạng Thái</a></th>
                <th>Hành động</th>
            </tr>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <?php
                    // Lấy số tiền đã thanh toán
                    $total_paid = $conn->query("SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE CusId = " . $order['CusId'])->fetchColumn();
                    $remaining_balance = $order['TotalPrice'] - $total_paid;
                    ?>
                    <tr>
                        <td><?= $order['OCode']; ?></td>
                        <td><?= htmlspecialchars($order['customer_fname'] . " " . $order['customer_lname']); ?></td>
                        <td><?= htmlspecialchars($order['emp_fname'] . " " . $order['emp_lname']); ?></td>
                        <td><?= htmlspecialchars($order['OrderTime']); ?></td>
                        <td><?= htmlspecialchars(number_format($order['TotalPrice'], 2)); ?></td>
                        <td><?= htmlspecialchars(number_format($remaining_balance, 2)); ?></td>
                        <td><?= htmlspecialchars($order['Status']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_order.php?id=<?= $order['OCode']; ?>" class="edit-btn">Sửa</a>
                            <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');" style="display:inline;">
                                <input type="hidden" name="delete_order_id" value="<?= $order['OCode']; ?>">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                            <?php if ($order['Status'] !== 'paid'): ?>
                                <button type="button" onclick="openPaymentModal(<?= $order['OCode']; ?>, <?= $remaining_balance; ?>)" class="pay-btn">Thanh toán</button>
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
</body>
</html>
