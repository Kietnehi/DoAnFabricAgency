<?php
session_start();
require 'connect.php'; // Connect to the database

$error = ""; // Default error message
$success = ""; // Success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        $error = "Mật khẩu mới không khớp!";
    } else {
        // Query to fetch user by username
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $error = "Tên đăng nhập không tồn tại.";
        } else {
            // Hash the new password
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);

            // Update the user's password in the database
            $stmt = $conn->prepare("UPDATE users SET password_hash = :password_hash WHERE username = :username");
            $stmt->execute(['password_hash' => $new_password_hash, 'username' => $username]);

            $success = "Mật khẩu đã được cập nhật thành công! Bạn có thể đăng nhập.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="app.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <title>Forgot Password</title>
    <style>
        .notification {
            display: none;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            z-index: 1000;
        }
        .notification.error {
            background-color: #f44336;
        }
    </style>
</head>

<body>
    <?php if (!empty($success)): ?>
        <div class="notification" id="success-notification"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="notification error" id="error-notification"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div id="wrapper">
        <div id="login-container">
            <form action="forgot_password.php" method="POST" id="form-forgot-password">
                <h1 class="form-heading">Quên mật khẩu</h1>
                <div class="form-group">
                    <i class="far fa-user"></i>
                    <input type="text" name="username" class="form-input" placeholder="Tên đăng nhập" required>
                </div>
                <div class="form-group">
                    <i class="fas fa-key"></i>
                    <input type="password" name="new_password" class="form-input" placeholder="Mật khẩu mới" required>
                </div>
                <div class="form-group">
                    <i class="fas fa-key"></i>
                    <input type="password" name="confirm_password" class="form-input" placeholder="Xác nhận mật khẩu mới" required>
                </div>
                <input type="submit" value="Cập nhật mật khẩu" class="form-submit">
                <div class="form-links">
                    <a href="login.php">Đăng nhập</a>
                </div>
            </form>
        </div>
        <div id="image-container">
            <img src="img/img10.png" alt="Cửa hàng vải">
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="app.js"></script>
    <script>
        $(document).ready(function() {
            <?php if (!empty($success)): ?>
                $('#success-notification').fadeIn().delay(3000).fadeOut();
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                $('#error-notification').fadeIn().delay(3000).fadeOut();
            <?php endif; ?>
        });
    </script>
</body>

</html>