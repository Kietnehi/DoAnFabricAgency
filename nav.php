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

<link rel="stylesheet" href="nav.css">

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

            <!-- Action Buttons -->
            <div class="action-buttons d-flex">
                <a class="nav-link" href="add_customer.php"><i class="fas fa-user-plus"></i> Thêm Khách hàng</a>
                <a class="nav-link" href="add_supplier.php"><i class="fas fa-user-plus"></i> Thêm Nhà cung cấp</a>
            </div>

            <!-- User Menu -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
            </ul>

            <!-- Social Icons -->
            <div class="social-icons d-none d-lg-flex">
                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                <a href="https://x.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </div>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Highlight active link
        const links = document.querySelectorAll('.navbar-nav .nav-link');
        const currentPage = window.location.pathname.split('/').pop();
        links.forEach(link => {
            if (link.getAttribute('href') === currentPage) {
                link.classList.add('active-link');
            }
        });

        // Add ripple effect
        const buttons = document.querySelectorAll('.nav-link, .action-buttons .nav-link');
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                const ripple = document.createElement('span');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                ripple.style.width = ripple.style.height = `${size}px`;
                ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
                ripple.style.top = `${e.clientY - rect.top - size / 2}px`;
                ripple.classList.add('ripple');
                button.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });
    });
</script>

<style>
    .ripple {
        position: absolute;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 50%;
        transform: scale(0);
        animation: rippleEffect 0.6s linear;
        pointer-events: none;
    }
    @keyframes rippleEffect {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
</style>

<!-- Bootstrap 5 JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
