<?php
session_start();
require 'connect.php'; // Connect to the database

$error = ""; // Default error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch user by username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: Check if user exists
    if (!$user) {
        $error = "Tên đăng nhập không tồn tại.";
        error_log("No user found for username: $username");
    } elseif (password_verify($password, $user['password_hash'])) {
        // Password matches
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Redirect to main page
        header("Location: index.php");
        exit();
    } else {
        // Invalid password
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        error_log("Password verification failed for user: $username");
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
    <title>Form Login</title>
</head>

<body>
    <div id="wrapper">
        <div id="login-container">
            <form action="login.php" method="POST" id="form-login">
                <h1 class="form-heading">Đăng nhập</h1>
                <div class="form-group">
                    <i class="far fa-user"></i>
                    <input type="text" name="username" class="form-input" placeholder="Tên đăng nhập" required>
                </div>
                <div class="form-group">
                    <i class="fas fa-key"></i>
                    <input type="password" name="password" class="form-input" placeholder="Mật khẩu" required>
                    <div id="eye">
                        <i class="far fa-eye"></i>
                    </div>
                </div>
                <input type="submit" value="Đăng nhập" class="form-submit">
                <div class="form-links">
                    <a href="register.php">Đăng ký</a>
                    <a href="forgot_password.php">Quên mật khẩu?</a>
                </div>
            </form>

            <?php if (!empty($error)): ?>
                <p style="color: red; text-align: center;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
        </div>
        <div id="image-container">
            <img src="img/img10.png" alt="Cửa hàng vải">
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="app.js"></script>
</body>

</html>
