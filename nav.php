<?php
// Start session if it hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    /* Custom styles for the navbar */
    .navbar {
        background-color: #212529;
    }
    .navbar-brand {
        font-weight: bold;
        color: #f8f9fa !important;
        font-size: 1.3rem;
        padding-left: 20px;
    }
    .navbar-nav .nav-link {
        font-size: 1.1rem;
        color: #f8f9fa !important;
        padding: 10px 15px;
    }
    .navbar-nav .nav-link:hover {
        color: #ffc107 !important;
    }
    .navbar-toggler-icon {
        background-color: #f8f9fa;
    }
    .nav-item .dropdown-menu {
        background-color: #343a40;
    }
    .nav-item .dropdown-menu .dropdown-item {
        color: #f8f9fa;
    }
    .nav-item .dropdown-menu .dropdown-item:hover {
        background-color: #495057;
    }
    .action-buttons .nav-link {
        background-color: #ffc107;
        color: #212529 !important;
        font-weight: bold;
        margin-right: 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .action-buttons .nav-link:hover {
        background-color: #e0a800;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- Brand Logo -->
        <a href="index.php" class="navbar-brand">GROUP.COM</a>
        
        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main Menu -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="customers.php">Quản lý Khách hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php">Quản lý Đơn hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="customer_payments.php">Thanh toán Khách hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="sales_statistics.php">Doanh thu</a></li>
                <li class="nav-item"><a class="nav-link" href="create_order.php">Tạo Đơn hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="product_manager.php">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="employees.php">Danh sách Nhân viên</a></li>
            </ul>

            <!-- Action Buttons -->
            <div class="action-buttons d-flex">
                <a class="nav-link" href="add_customer.php"><i class="bi bi-person-plus"></i> Thêm Khách hàng</a>
                <a class="nav-link" href="add_employee.php"><i class="bi bi-person-plus"></i> Thêm Nhân viên</a>
            </div>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-box-arrow-right"></i> Tài khoản
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
