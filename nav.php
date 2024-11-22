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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
    /* Custom styles for the navbar */
    .navbar {
        background: linear-gradient(90deg, #212529, #343a40);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
    }
    .navbar:hover {
        background: linear-gradient(90deg, #343a40, #212529);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
    .navbar-brand {
        font-weight: bold;
        color: #ffc107 !important;
        font-size: 1.5rem;
        padding-left: 20px;
        transition: color 0.3s ease;
    }
    .navbar-brand:hover {
        color: #ffffff !important;
        text-shadow: 0 0 10px #ffc107;
    }
    .navbar-nav .nav-link {
        font-size: 1.1rem;
        color: #f8f9fa !important;
        margin: 0 8px;
        transition: color 0.3s ease, transform 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
        color: #ffc107 !important;
        transform: translateY(-3px);
    }
    .navbar-toggler-icon {
        background-color: #f8f9fa;
    }
    .action-buttons .nav-link {
        background-color: #ffc107;
        color: #212529 !important;
        font-weight: bold;
        margin-right: 10px;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .action-buttons .nav-link:hover {
        background-color: #e0a800;
        transform: scale(1.1);
    }
    .dropdown-menu {
        background: #495057;
        border: none;
        border-radius: 8px;
        animation: fadeIn 0.3s ease-in-out;
    }
    .dropdown-menu .dropdown-item {
        color: #f8f9fa;
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    .dropdown-menu .dropdown-item:hover {
        background-color: #343a40;
        color: #ffc107;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .navbar .social-icons a {
        color: #f8f9fa;
        margin: 0 8px;
        font-size: 1.2rem;
        transition: color 0.3s ease, transform 0.3s ease;
    }
    .navbar .social-icons a:hover {
        color: #ffc107;
        transform: rotate(15deg) scale(1.2);
    }.dropdown-menu {
    animation: slideIn 0.4s ease-in-out;
}
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}.nav-link, .action-buttons .nav-link {
    position: relative;
    overflow: hidden;
}

.nav-link::after, .action-buttons .nav-link::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.5s ease, height 0.5s ease, opacity 0.5s ease;
    opacity: 0;
}

.nav-link:hover::after, .action-buttons .nav-link:hover::after {
    width: 300px;
    height: 300px;
    opacity: 1;
}
</style>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <!-- Brand Logo -->
        <a href="index.php" class="navbar-brand"><i class="fas fa-store"></i> GROUP.COM</a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Main Menu -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="customers.php"><i class="fas fa-users"></i> Quản lý Khách hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php"><i class="fas fa-box"></i> Quản lý Đơn hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="customer_payments.php"><i class="fas fa-credit-card"></i> Thanh toán Khách hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="sales_statistics.php"><i class="fas fa-chart-line"></i> Doanh thu</a></li>
                <li class="nav-item"><a class="nav-link" href="create_order.php"><i class="fas fa-plus-circle"></i> Tạo Đơn hàng</a></li>
                <li class="nav-item"><a class="nav-link" href="product_manager.php"><i class="fas fa-box-open"></i> Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link" href="employees.php"><i class="fas fa-user-tie"></i> Nhân viên</a></li>
            </ul>

            <!-- Social Icons -->
            <div class="social-icons d-none d-lg-flex">
                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons d-flex">
                <a class="nav-link" href="add_customer.php"><i class="fas fa-user-plus"></i> Thêm Khách hàng</a>
                <a class="nav-link" href="add_employee.php"><i class="fas fa-user-plus"></i> Thêm Nhân viên</a>
            </div>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-cog"></i> Tài khoản
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
