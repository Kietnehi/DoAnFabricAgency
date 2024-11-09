<?php
// Khởi động phiên nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">    

<div class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="navbar-toggle" data-target="#mobile_menu" data-toggle="collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="index.php" class="navbar-brand">GROUP.COM</a>
        </div>

        <div class="navbar-collapse collapse" id="mobile_menu">
            <!-- Menu chính --> 
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php">Home</a></li>
                
                <li><a href="customers.php">Quản lý Khách hàng</a></li>
                <li><a href="orders.php">Quản lý Đơn hàng</a></li>
                <li><a href="customer_payments.php">Thanh toán Khách hàng</a></li>
                <li><a href="sales_statistics.php">Doanh thu</a></li>
                <li><a href="create_order.php">Tạo Đơn hàng</a></li>
                <li><a href="product_manager.php">Sản phẩm</a></li> <!-- Nút Sản phẩm được thêm vào đây -->
            </ul>

            <!-- Tìm kiếm -->
            <ul class="nav navbar-nav navbar-form">
                <form class="navbar-form" action="search.php" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="Search Anything Here..." class="form-control">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                    </div>
                </form>
            </ul>

            <!-- Menu người dùng -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-log-out"></span> Logout <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
