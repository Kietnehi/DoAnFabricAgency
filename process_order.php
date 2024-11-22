<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $total_amount = $_POST['total_amount'];
    $order_time = date('Y-m-d H:i:s'); // Thời gian tạo đơn
    $handle_time = $order_time;       // Thời gian xử lý đơn (cùng lúc tạo đơn)
    $status = 'new';

    try {
        // Bắt đầu giao dịch
        $conn->beginTransaction();

        // Thêm đơn hàng vào bảng `orders`
        $sql = "INSERT INTO orders (CusId, ECode, TotalPrice, OrderTime, HandleTime, Status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id, $employee_id, $total_amount, $order_time, $handle_time, $status]);

        // Lấy mã đơn hàng (OCode) vừa được tạo
        $order_id = $conn->lastInsertId();

        // Duyệt qua các sản phẩm đã chọn và thêm vào bảng `order_detail`
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $bolt_id => $quantity) {
                if ($quantity > 0) {
                    // Lấy giá và thông tin tồn kho từ bảng `bolt` và `category`
                    $sql = "
                        SELECT bolt.Length AS bolt_length, category.Price AS unit_price, category.RemainQuantity AS stock_quantity 
                        FROM bolt
                        JOIN category ON bolt.CCode = category.CCode
                        WHERE bolt.BCode = ?
                    ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$bolt_id]);
                    $bolt = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$bolt) {
                        throw new Exception("Cuộn vải với ID $bolt_id không tồn tại.");
                    }

                    if ($bolt['stock_quantity'] < $quantity) {
                        throw new Exception("Không đủ tồn kho cho cuộn vải ID $bolt_id.");
                    }

                    $unit_price = $bolt['unit_price'];
                    $total_price = $unit_price * $quantity;

                    // Thêm chi tiết đơn hàng vào bảng `order_detail`
                    $sql = "INSERT INTO order_detail (OCode, BCode, Quantity, UnitPrice, TotalPrice) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$order_id, $bolt_id, $quantity, $unit_price, $total_price]);

                    // Cập nhật tồn kho trong bảng `category`
                    $new_stock_quantity = $bolt['stock_quantity'] - $quantity;
                    $sql = "UPDATE category SET RemainQuantity = ? WHERE CCode = (SELECT CCode FROM bolt WHERE BCode = ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$new_stock_quantity, $bolt_id]);
                }
            }
        }

        // Xác nhận giao dịch
        $conn->commit();

        // Chuyển đến trang chi tiết đơn hàng
        header("Location: order_details.php?id=$order_id");
        exit();
    } catch (Exception $e) {
        // Hủy giao dịch nếu có lỗi
        $conn->rollBack();
        echo "Lỗi khi tạo đơn hàng: " . $e->getMessage();
    }
}
?>
