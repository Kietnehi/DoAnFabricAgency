<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $total_amount = $_POST['total_amount'];
    $order_date = date('Y-m-d H:i:s');
    $status = 'new';

    try {
        // Bắt đầu giao dịch
        $conn->beginTransaction();

        // Tạo đơn hàng
        $sql = "INSERT INTO orders (customer_id, employee_id, order_date, total_amount, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id, $employee_id, $order_date, $total_amount, $status]);

        // Lấy ID của đơn hàng vừa tạo
        $order_id = $conn->lastInsertId();

        // Kiểm tra và thêm các cuộn vải vào đơn hàng
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $roll_id => $quantity) {
                if ($quantity > 0) {
                    // Lấy thông tin về loại vải và tồn kho từ bảng fabric_rolls và fabric_types
                    $sql = "SELECT fabric_rolls.length, fabric_types.fabric_type_id, fabric_types.quantity AS fabric_stock
                            FROM fabric_rolls
                            JOIN fabric_types ON fabric_rolls.fabric_type_id = fabric_types.fabric_type_id
                            WHERE roll_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$roll_id]);
                    $roll = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$roll) {
                        throw new Exception("Cuộn vải với ID $roll_id không tồn tại.");
                    }

                    // Kiểm tra nếu tồn kho của loại vải đủ để đáp ứng số lượng đặt hàng
                    if ($roll['fabric_stock'] < $quantity) {
                        throw new Exception("Không đủ tồn kho cho loại vải ID " . $roll['fabric_type_id']);
                    }

                    // Thêm cuộn vải vào chi tiết đơn hàng
                    $sql = "INSERT INTO order_fabric_rolls (order_id, roll_id) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$order_id, $roll_id]);

                    // Cập nhật tồn kho cho loại vải trong bảng fabric_types
                    $new_fabric_stock = $roll['fabric_stock'] - $quantity;
                    $sql = "UPDATE fabric_types SET quantity = ? WHERE fabric_type_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$new_fabric_stock, $roll['fabric_type_id']]);

                    // Cập nhật tồn kho trong bảng inventory cho từng mục nhập liên quan đến fabric_type_id
                    $sql_inventory = "SELECT inventory_id, quantity FROM inventory WHERE fabric_type_id = ? ORDER BY purchase_date ASC";
                    $stmt_inventory = $conn->prepare($sql_inventory);
                    $stmt_inventory->execute([$roll['fabric_type_id']]);
                    $inventories = $stmt_inventory->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($inventories as $inventory) {
                        if ($quantity <= 0) break;

                        if ($inventory['quantity'] >= $quantity) {
                            // Giảm trực tiếp từ mục nhập hiện tại
                            $new_inventory_qty = $inventory['quantity'] - $quantity;
                            $sql_update_inventory = "UPDATE inventory SET quantity = ? WHERE inventory_id = ?";
                            $stmt_update = $conn->prepare($sql_update_inventory);
                            $stmt_update->execute([$new_inventory_qty, $inventory['inventory_id']]);
                            $quantity = 0; // Đặt lại quantity sau khi trừ hết
                        } else {
                            // Giảm số lượng từ mục hiện tại và chuyển sang mục tiếp theo
                            $sql_update_inventory = "UPDATE inventory SET quantity = 0 WHERE inventory_id = ?";
                            $stmt_update = $conn->prepare($sql_update_inventory);
                            $stmt_update->execute([$inventory['inventory_id']]);
                            $quantity -= $inventory['quantity']; // Cập nhật số lượng còn lại cần trừ
                        }
                    }
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
ob_end_flush();
?>
