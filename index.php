<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include "nav.php";

// Xử lý lọc thời gian
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

// Thống kê số lượng khách hàng, đơn hàng và tổng doanh thu
$customer_count = $conn->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$order_count = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue_query = "SELECT SUM(total_amount) FROM orders";
if ($start_date && $end_date) {
    $total_revenue_query .= " WHERE order_date BETWEEN '$start_date' AND '$end_date'";
}
$total_revenue = $conn->query($total_revenue_query)->fetchColumn();

// Hoạt động gần đây
$recent_orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
$recent_customers = $conn->query("SELECT * FROM customers ORDER BY customer_id DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Top khách hàng theo doanh thu
$top_customers = $conn->query("
    SELECT first_name, last_name, SUM(total_amount) AS revenue 
    FROM orders 
    JOIN customers ON orders.customer_id = customers.customer_id 
    GROUP BY customers.customer_id 
    ORDER BY revenue DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Lấy dữ liệu doanh thu theo tháng cho biểu đồ
$monthly_revenue_query = "
    SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, SUM(total_amount) AS revenue 
    FROM orders";
if ($start_date && $end_date) {
    $monthly_revenue_query .= " WHERE order_date BETWEEN '$start_date' AND '$end_date'";
}
$monthly_revenue_query .= " GROUP BY month ORDER BY month";

$monthly_revenue = $conn->query($monthly_revenue_query)->fetchAll(PDO::FETCH_ASSOC);

// Kiểm tra kết quả truy vấn


$monthly_labels = [];
$monthly_data = [];
foreach ($monthly_revenue as $row) {
    $monthly_labels[] = $row['month'];
    $monthly_data[] = $row['revenue'];
}


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Fabric Agency - Bảng Điều Khiển</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styles */
        body { font-family: Arial, sans-serif; background: #f9f9fb; margin: 0; padding: 0; }
        .container { max-width: 1200px; margin: 20px auto; }
        .stat-card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); display: flex; align-items: center; margin-bottom: 20px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-icon { font-size: 40px; color: #007bff; margin-right: 15px; }
        .stat-content h3 { margin: 0; font-size: 24px; color: #333; }
        .stat-content p { margin: 0; color: #777; }
        .recent-activity, .top-customers, .chart-container, .notifications { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); margin-top: 20px; }
        .quick-links { margin-top: 30px; }
        .quick-links a { display: inline-block; margin: 5px; padding: 10px 15px; color: #fff; background: #007bff; border-radius: 5px; text-decoration: none; transition: background 0.2s; }
        .quick-links a:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <h1>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="stat-card">
                <span class="stat-icon glyphicon glyphicon-user"></span>
                <div class="stat-content">
                    <h3><?php echo $customer_count; ?></h3>
                    <p>Khách Hàng</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <span class="stat-icon glyphicon glyphicon-shopping-cart"></span>
                <div class="stat-content">
                    <h3><?php echo $order_count; ?></h3>
                    <p>Đơn Hàng</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <span class="stat-icon glyphicon glyphicon-usd"></span>
                <div class="stat-content">
                    <h3><?php echo number_format($total_revenue, 2); ?> USD</h3>
                    <p>Doanh Thu Tổng</p>
                </div>
            </div>
        </div>
    </div>
    <?php
// Số lượng khách hàng có công nợ quá hạn (ví dụ: công nợ lớn hơn 0 và quá hạn)
$overdue_customers_count = $conn->query("
    SELECT COUNT(*) 
    FROM customers 
    WHERE outstanding_balance > 0 AND warning_status = 1
")->fetchColumn();

// Lấy thông tin đơn hàng mới nhất
$newest_order = $conn->query("
    SELECT order_id, order_date 
    FROM orders 
    ORDER BY order_date DESC 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
?>
    <!-- Bộ lọc thời gian -->
    <form method="GET" class="form-inline">
        <label for="start_date">Từ ngày:</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>">
        <label for="end_date">Đến ngày:</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo htmlspecialchars($end_date); ?>">
        <button type="submit" class="btn btn-primary">Lọc</button>
    </form>

   <!-- Thông báo nổi bật -->
<div class="notifications">
    <h3>Thông Báo</h3>
    <ul>
        <li>Có <strong><?php echo $overdue_customers_count; ?></strong> khách hàng có công nợ quá hạn.</li>
        <?php if ($newest_order): ?>
            <li>Đơn hàng #<?php echo htmlspecialchars($newest_order['order_id']); ?> vừa được tạo vào <?php echo htmlspecialchars($newest_order['order_date']); ?>.</li>
        <?php else: ?>
            <li>Chưa có đơn hàng nào.</li>
        <?php endif; ?>
    </ul>
</div>

    <!-- Hoạt Động Gần Đây -->
    <div class="recent-activity">
        <h3>Hoạt Động Gần Đây</h3>
        <h4>Đơn Hàng Mới</h4>
        <ul>
            <?php foreach ($recent_orders as $order): ?>
                <li>Đơn hàng #<?php echo $order['order_id']; ?> - <?php echo htmlspecialchars($order['order_date']); ?> - <?php echo number_format($order['total_amount'], 2); ?> USD</li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Top Khách Hàng -->
    <div class="top-customers">
        <h3>Top Khách Hàng</h3>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Tên Khách Hàng</th>
                <th>Doanh Thu</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($top_customers as $customer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></td>
                    <td><?php echo number_format($customer['revenue'], 2); ?> USD</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Biểu Đồ Doanh Thu Theo Tháng -->
    <div class="chart-container">
        <h3>Doanh Thu Theo Tháng</h3>
        <canvas id="revenueChart"></canvas>
    </div>

    <div class="quick-links">
        <h3>Liên Kết Nhanh</h3>
        <a href="create_order.php">Tạo Đơn Hàng Mới</a>
        <a href="customers.php">Quản Lý Khách Hàng</a>
        <a href="sales_statistics.php">Thống Kê Doanh Thu</a>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    const revenueData = {
        labels: <?php echo json_encode($monthly_labels); ?>,
        datasets: [{
            label: 'Doanh Thu (USD)',
            data: <?php echo json_encode($monthly_data); ?>,
            backgroundColor: 'rgba(0, 123, 255, 0.2)',
            borderColor: 'rgba(0, 123, 255, 1)',
            borderWidth: 2,
            fill: true
        }]
    };

    const config = {
        type: 'line',
        data: revenueData,
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    };

    const revenueChart = new Chart(document.getElementById('revenueChart'), config);
</script>
</body>
</html>
