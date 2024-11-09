<?php
include "connect.php"; // Đảm bảo kết nối cơ sở dữ liệu
include "nav.php";
try {
    // Tính tổng doanh thu của các đơn hàng đã thanh toán
    $stmt = $conn->prepare("SELECT SUM(total_amount) as total_revenue FROM orders WHERE status = 'paid'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRevenue = $result['total_revenue'] ?? 0;
} catch (PDOException $e) {
    echo "Lỗi khi truy vấn dữ liệu: " . $e->getMessage();
    $totalRevenue = 0;
}

// Đoạn mã HTML hiển thị tổng doanh thu
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống Kê Doanh Thu Bán Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .container {
            max-width: 900px;
            width: 100%;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            justify-content: center;
            align-items: center;
        }
        .filter-form input[type="date"], .filter-form button {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s;
            outline: none;
        }
        .filter-form input[type="date"]:focus {
            border-color: #007bff;
        }
        .quick-filter button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .quick-filter button:hover {
            background-color: #0056b3;
        }
        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            animation: fadeIn 0.6s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .total-revenue {
            font-size: 18px;
            color: #28a745;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        canvas {
            max-width: 400px;
            max-height: 200px;
            width: 100%;
            height: auto;
        }
        .message {
            color: #ff6b6b;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Thống Kê Doanh Thu Bán Hàng</h1>

        <div class="total-revenue" id="totalRevenue">
        <span style="color: #28a745; font-size: 24px;">Tổng doanh thu: $<?php echo number_format($totalRevenue, 2); ?></span>
</div>

        <!-- Form chọn ngày -->
        <form id="filterForm" class="filter-form">
            <label for="date" style="font-weight: bold; color: #333;">Chọn Ngày:</label>
            <input type="date" id="date" name="date">
            <div class="quick-filter">
                <button type="button" onclick="setQuickFilter('today')">Hôm Nay</button>
                <button type="button" onclick="setQuickFilter('week')">Tuần Này</button>
                <button type="button" onclick="setQuickFilter('month')">Tháng Này</button>
                <button type="button" onclick="setQuickFilter('year')">Năm Này</button>
            </div>
        </form>

        <!-- Biểu đồ doanh thu theo tháng -->
        <div class="chart-container">
            <h2 style="margin-bottom: 10px;">Doanh Thu Theo Tháng</h2>
            <canvas id="monthlyRevenueChart"></canvas>
        </div>

        <!-- Biểu đồ doanh thu theo loại vải -->
        <div class="chart-container">
            <h2 style="margin-bottom: 10px;">Doanh Thu Theo Loại Vải</h2>
            <canvas id="fabricRevenueChart"></canvas>
        </div>

        <!-- Biểu đồ doanh thu theo khách hàng -->
        <div class="chart-container">
            <h2 style="margin-bottom: 10px;">Doanh Thu Theo Khách Hàng</h2>
            <canvas id="customerRevenueChart"></canvas>
        </div>
        
        <!-- Thông báo nếu không có dữ liệu -->
        <div id="noDataMessage" class="message" style="display: none;">Không có dữ liệu cho ngày đã chọn.</div>
    </div>

    <script>
        let monthlyRevenueChart, fabricRevenueChart, customerRevenueChart;

        function renderCharts(data) {
            const totalRevenue = data.total_revenue || 0;
            document.getElementById('noDataMessage').style.display = data.has_data ? 'none' : 'block';

            const monthlyLabels = data.monthly_revenue.map(item => item.month);
            const monthlyData = data.monthly_revenue.map(item => item.revenue);

            if (monthlyRevenueChart) monthlyRevenueChart.destroy();
            monthlyRevenueChart = new Chart(document.getElementById('monthlyRevenueChart'), {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Doanh Thu (USD)',
                        data: monthlyData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        fill: false,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            const fabricLabels = data.fabric_revenue.map(item => item.fabric_name);
            const fabricData = data.fabric_revenue.map(item => item.revenue);

            if (fabricRevenueChart) fabricRevenueChart.destroy();
            fabricRevenueChart = new Chart(document.getElementById('fabricRevenueChart'), {
                type: 'bar',
                data: {
                    labels: fabricLabels,
                    datasets: [{
                        label: 'Doanh Thu (USD)',
                        data: fabricData,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });

            const customerLabels = data.customer_revenue.map(item => item.customer_name);
            const customerData = data.customer_revenue.map(item => item.revenue);

            if (customerRevenueChart) customerRevenueChart.destroy();
            customerRevenueChart = new Chart(document.getElementById('customerRevenueChart'), {
                type: 'pie',
                data: {
                    labels: customerLabels,
                    datasets: [{
                        label: 'Doanh Thu (USD)',
                        data: customerData,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        function filterData(date = '') {
            fetch(`get_sales_data.php?date=${date}`)
                .then(response => response.json())
                .then(data => renderCharts(data))
                .catch(error => console.error('Error loading data:', error));
        }

        function setQuickFilter(period) {
            const dateInput = document.getElementById('date');
            const today = new Date();
            switch (period) {
                case 'today':
                    dateInput.valueAsDate = today;
                    break;
                case 'week':
                    const startOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
                    dateInput.valueAsDate = startOfWeek;
                    break;
                case 'month':
                    const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    dateInput.valueAsDate = startOfMonth;
                    break;
                case 'year':
                    const startOfYear = new Date(today.getFullYear(), 0, 1);
                    dateInput.valueAsDate = startOfYear;
                    break;
            }
            filterData(dateInput.value);
        }

        document.getElementById('date').addEventListener('change', () => filterData(document.getElementById('date').value));
        filterData(); // Tải dữ liệu lần đầu
    </script>
</body>
</html>
