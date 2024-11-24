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
$customer_count = $conn->query("SELECT COUNT(*) FROM customer")->fetchColumn();
$order_count = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$total_revenue_query = "SELECT SUM(TotalPrice) FROM orders";
if ($start_date && $end_date) {
    $total_revenue_query .= " WHERE OrderTime BETWEEN '$start_date' AND '$end_date'";
}
$total_revenue = $conn->query($total_revenue_query)->fetchColumn();

// Hoạt động gần đây
$recent_orders = $conn->query("SELECT * FROM orders ORDER BY OrderTime DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$recent_customers = $conn->query("SELECT * FROM customer ORDER BY CusId DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

// Top khách hàng theo doanh thu
$top_customers = $conn->query("
    SELECT Fname, Lname, SUM(TotalPrice) AS revenue 
    FROM orders 
    JOIN customer ON orders.CusId = customer.CusId 
    GROUP BY customer.CusId 
    ORDER BY revenue DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Lấy dữ liệu doanh thu theo tháng cho biểu đồ
$monthly_revenue_query = "
    SELECT DATE_FORMAT(OrderTime, '%Y-%m') AS month, SUM(TotalPrice) AS revenue 
    FROM orders";
if ($start_date && $end_date) {
    $monthly_revenue_query .= " WHERE OrderTime BETWEEN '$start_date' AND '$end_date'";
}
$monthly_revenue_query .= " GROUP BY month ORDER BY month";

$monthly_revenue = $conn->query($monthly_revenue_query)->fetchAll(PDO::FETCH_ASSOC);

$monthly_labels = [];
$monthly_data = [];
foreach ($monthly_revenue as $row) {
    $monthly_labels[] = $row['month'];
    $monthly_data[] = $row['revenue'];
}

// Số lượng khách hàng có công nợ quá hạn
$overdue_customers_count = $conn->query("
    SELECT COUNT(*) 
    FROM customer 
    WHERE Dept > 0
")->fetchColumn();

// Lấy thông tin đơn hàng mới nhất
$newest_order = $conn->query("
    SELECT OCode, OrderTime 
    FROM orders 
    ORDER BY OrderTime DESC 
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);

$newest_order_id = isset($newest_order['OCode']) ? $newest_order['OCode'] : 'N/A';
$newest_order_date = isset($newest_order['OrderTime']) ? $newest_order['OrderTime'] : 'N/A';

// Biểu đồ trạng thái đơn hàng
$order_status_data = $conn->query("
    SELECT Status, COUNT(*) AS count 
    FROM orders 
    GROUP BY Status
")->fetchAll(PDO::FETCH_ASSOC);

$order_status_labels = array_column($order_status_data, 'Status');
$order_status_counts = array_column($order_status_data, 'count');

// Biểu đồ Top khách hàng theo doanh thu
$top_customers_chart = $conn->query("
    SELECT CONCAT(Fname, ' ', Lname) AS customer_name, SUM(TotalPrice) AS revenue 
    FROM orders 
    JOIN customer ON orders.CusId = customer.CusId 
    GROUP BY customer.CusId 
    ORDER BY revenue DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$customer_names = array_column($top_customers_chart, 'customer_name');
$customer_revenue = array_column($top_customers_chart, 'revenue');

// Biểu đồ công nợ theo tháng
$monthly_outstanding_query = "
    SELECT DATE_FORMAT(OrderTime, '%Y-%m') AS month, SUM(Dept) AS outstanding 
    FROM customer 
    JOIN orders ON orders.CusId = customer.CusId 
    WHERE Dept > 0
    GROUP BY month
    ORDER BY month";

$monthly_outstanding_data = $conn->query($monthly_outstanding_query)->fetchAll(PDO::FETCH_ASSOC);

$outstanding_labels = array_column($monthly_outstanding_data, 'month');
$outstanding_data = array_column($monthly_outstanding_data, 'outstanding');
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Fabric Agency - Bảng Điều Khiển</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="indexs.css">
</head>

<body>
    <div class="container">
        <h1>Chào mừng, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

        <div class="row">
            <div class="col-md-4">
                <div class="stat-card" data-aos="fade-up">
                <i class="fa fa-users fa-3x" aria-hidden="true" style="margin-right: 10px;"></i>
                    <div class="stat-content">
                        <h3><?php echo $customer_count; ?></h3>
                        <p>Khách Hàng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card" data-aos="fade-up">
                <i class="fa fa-shopping-cart fa-3x" aria-hidden="true" style="margin-right: 10px;"></i>
                    <div class="stat-content">
                        <h3><?php echo $order_count; ?></h3>
                        <p>Đơn Hàng</p>
                    </div>
                </div>
            </div>  
            <div class="col-md-4">
                <div class="stat-card" data-aos="fade-up">
                <i class="fas fa-money-bill fa-3x" aria-hidden="true" style="margin-right: 10px;"></i>
                    <div class="stat-content">
                        <h3><?php echo number_format($total_revenue, 2); ?> USD</h3>
                        <p>Doanh Thu Tổng</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bộ lọc thời gian -->
        <form method="GET" class="form-inline">
            <label for="start_date">Từ ngày:</label>
            <input type="text" name="start_date" id="start_date" class="form-control" value="<?php echo htmlspecialchars($start_date); ?>" placeholder="yyyy-mm-dd">
            <label for="end_date">Đến ngày:</label>
            <input type="text" name="end_date" id="end_date" class="form-control" style="display:inline-block;" value="<?php echo htmlspecialchars($end_date); ?>" placeholder="yyyy-mm-dd">
            <button type="submit" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i>Lọc</button>
        </form>
        <div class="notifications">
            <h3>Thông Báo</h3>
            <ul>
                <li>Có <strong><?php echo $overdue_customers_count; ?></strong> khách hàng có công nợ quá hạn.</li>
                <li>Đơn hàng #<?php echo htmlspecialchars($newest_order_id); ?> vừa được tạo vào <?php echo htmlspecialchars($newest_order_date); ?>.</li>
            </ul>
        </div>

        <div class="recent-activity">
            <h3>Hoạt Động Gần Đây</h3>
            <h4>Đơn Hàng Mới</h4>
            <ul>
                <?php foreach ($recent_orders as $order): ?>
                    <li>Đơn hàng #<?php echo $order['OCode']; ?> - <?php echo htmlspecialchars($order['OrderTime']); ?> - <?php echo number_format($order['TotalPrice'], 2); ?> USD</li>
                <?php endforeach; ?>
            </ul>
        </div>
        
        <div class="top-customers">
            <h3>Top Khách Hàng</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tên Khách Hàng</th>
                        <th>Doanh Thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_customers as $customer): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($customer['Fname'] . ' ' . $customer['Lname']); ?></td>
                            <td><?php echo number_format($customer['revenue'], 2); ?> USD</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="chart-grid">
            <!-- Biểu Đồ Doanh Thu Theo Tháng -->
            <div class="chart-container" data-aos="fade-up">
                <h3>Doanh Thu Theo Tháng</h3>
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Biểu đồ trạng thái đơn hàng -->
            <div class="chart-container" data-aos="fade-up">
                <h3>Trạng Thái Đơn Hàng</h3>
                <canvas id="statusChart"></canvas>
            </div>

            <!-- Biểu đồ thanh top khách hàng -->
            <div class="chart-container" data-aos="fade-up">
                <h3>Top 5 Khách Hàng Theo Doanh Thu</h3>
                <canvas id="topCustomersChart"></canvas>
            </div>

            <!-- Biểu đồ công nợ theo tháng -->
            <div class="chart-container" data-aos="fade-up">
                <h3>Công Nợ Theo Tháng</h3>
                <canvas id="outstandingChart"></canvas>
            </div>
        </div>
        <div class="quick-links">
            <h3>Liên Kết Nhanh</h3>
            <a href="create_order.php">Tạo Đơn Hàng Mới</a>
            <a href="customers.php">Quản Lý Khách Hàng</a>
            <a href="sales_statistics.php">Thống Kê Doanh Thu</a>
        </div>
    </div>

    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        $(function() {
            $("#start_date, #end_date").datepicker({
                dateFormat: 'yy-mm-dd',
                maxDate: new Date()
            });
        });

        // Biểu đồ Doanh Thu Theo Tháng (Biểu đồ Đường)
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

        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: revenueData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Biểu đồ Trạng Thái Đơn Hàng (Biểu đồ Tròn)
        const statusData = {
            labels: <?php echo json_encode($order_status_labels); ?>,
            datasets: [{
                data: <?php echo json_encode($order_status_counts); ?>,
                backgroundColor: ['#4caf50', '#ff9800', '#f44336', '#2196f3', '#9e9e9e']
            }]
        };

        const statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: statusData,
            options: {
                responsive: true
            }
        });

        // Biểu đồ Top Khách Hàng (Biểu đồ Thanh)
        const topCustomersData = {
            labels: <?php echo json_encode($customer_names); ?>,
            datasets: [{
                label: 'Doanh Thu (USD)',
                data: <?php echo json_encode($customer_revenue); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        const topCustomersChart = new Chart(document.getElementById('topCustomersChart'), {
            type: 'bar',
            data: topCustomersData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh Thu (USD)'
                        }
                    }
                }
            }
        });

        // Biểu đồ Công Nợ Hàng Tháng (Biểu đồ Đường)
        const outstandingData = {
    labels: <?php echo json_encode($outstanding_labels); ?>,
    datasets: [{
        label: 'Công Nợ (USD)',
        data: <?php echo json_encode($outstanding_data); ?>,
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        borderColor: 'rgba(255, 99, 132, 1)',
        borderWidth: 2,
        fill: true
    }]
};


        const outstandingChart = new Chart(document.getElementById('outstandingChart'), {
            type: 'line',
            data: outstandingData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Công Nợ (USD)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>