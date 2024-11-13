<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

// Kiểm tra xem có ID đơn hàng hay không
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Truy vấn để kiểm tra sự tồn tại của đơn hàng
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Xóa đơn hàng nếu đã xác nhận
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xóa các dòng liên quan trong Order_Fabric_Rolls nếu có
            $conn->prepare("DELETE FROM Order_Fabric_Rolls WHERE order_id = :order_id")->execute(['order_id' => $order_id]);

            // Xóa các thanh toán liên quan trong Order_Payments nếu có
            $conn->prepare("DELETE FROM Order_Payments WHERE order_id = :order_id")->execute(['order_id' => $order_id]);

            // Xóa đơn hàng chính
            $stmt = $conn->prepare("DELETE FROM Orders WHERE order_id = :order_id");
            $stmt->execute(['order_id' => $order_id]);

            // Chuyển hướng sau khi xóa thành công
            header("Location: orders.php");
            exit();
        }
    } else {
        $error = "Đơn hàng không tồn tại!";
    }
} else {
    header("Location: orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa Đơn Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 500px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #f44336;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        button {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #d32f2f;
        }

        a.cancel {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #333;
            padding: 10px 20px;
            background-color: #ddd;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a.cancel:hover {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Xóa Đơn Hàng</h1>
        
        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
            <a href="orders.php" class="cancel">Quay lại danh sách đơn hàng</a>
        <?php else: ?>
            <p>Bạn có chắc chắn muốn xóa đơn hàng này không?</p>
            <form method="POST">
                <button type="submit">Xóa Đơn Hàng</button>
                <a href="orders.php" class="cancel">Hủy bỏ</a>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
