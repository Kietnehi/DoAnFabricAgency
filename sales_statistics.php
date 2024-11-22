<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "connect.php"; // Ensure database connection
include "nav.php";

try {
    // Calculate total revenue from completed orders
    $stmt = $conn->prepare("SELECT SUM(TotalPrice) as total_revenue FROM orders WHERE Status = 'Completed'");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRevenue = $result['total_revenue'] ?? 0;
} catch (PDOException $e) {
    echo "Error querying data: " . $e->getMessage();
    $totalRevenue = 0;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống Kê Doanh Thu Bán Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="sales_statistic.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        <!-- Chart containers for different types of revenue -->
        <div class="chart-container">
            <h2 style="margin-bottom: 10px;">Doanh Thu Theo Tháng</h2>
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
        <div class="chart-container">
            <h2 style="margin-bottom: 10px;">Doanh Thu Theo Loại Vải</h2>
            <canvas id="fabricRevenueChart"></canvas>
        </div>
        <div class="chart-container">
            <h2 style="margin-bottom: 10px;">Doanh Thu Theo Khách Hàng</h2>
            <canvas id="customerRevenueChart"></canvas>
        </div>
        
        <!-- Notification if there is no data -->
        <div id="noDataMessage" class="message" style="display: none;">Không có dữ liệu cho ngày đã chọn.</div>
    </div>

    <script>
        let monthlyRevenueChart, fabricRevenueChart, customerRevenueChart;

        // Function to render charts based on data
        function renderCharts(data) {
            document.getElementById('noDataMessage').style.display = data.has_data ? 'none' : 'block';

// Dữ liệu và nhãn cho biểu đồ doanh thu theo tháng
const monthlyLabels = data.monthly_revenue.map(item => item.month);
const monthlyData = data.monthly_revenue.map(item => parseFloat(item.revenue));

if (monthlyRevenueChart) monthlyRevenueChart.destroy(); // Hủy biểu đồ trước đó nếu có

// Biểu đồ tuyến (line chart) cho doanh thu theo tháng
monthlyRevenueChart = new Chart(document.getElementById('monthlyRevenueChart'), {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Doanh Thu (USD)',
            data: monthlyData,
            borderColor: 'rgba(75, 192, 192, 1)', // Đường màu xanh dương
            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Vùng tô mờ
            pointBackgroundColor: 'rgba(75, 192, 192, 1)', // Điểm dữ liệu màu xanh
            pointRadius: 5, // Kích thước điểm dữ liệu
            borderWidth: 2 // Độ rộng đường
        }]
    },
    options: {
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function (context) {
                        return `Doanh thu: $${context.raw.toFixed(2)}`;
                    }
                }
            }
        },
        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Tháng',
                    font: {
                        size: 14
                    }
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Doanh Thu (USD)',
                    font: {
                        size: 14
                    }
                },
                ticks: {
                    callback: function (value) {
                        return `$${value}`;
                    }
                }
            }
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
                    scales: { y: { beginAtZero: true } }
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
                options: { responsive: true }
            });
        }

        // Fetch data and render charts
        function filterData(date = '') {
            fetch(`get_sales_data.php?date=${date}`)
                .then(response => response.json())
                .then(data => renderCharts(data))
                .catch(error => console.error('Error loading data:', error));
        }

        // Set quick filter based on period
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
        filterData(); // Initial data load
    </script>
</body>
</html>
