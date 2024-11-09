<?php
require 'connect.php';

function updateCustomerBalance($conn, $customer_id, $payment_amount) {
    // Cập nhật công nợ của khách hàng
    $stmt = $conn->prepare("UPDATE customers SET outstanding_balance = outstanding_balance - ? WHERE customer_id = ?");
    $stmt->execute([$payment_amount, $customer_id]);

    // Kiểm tra công nợ để cập nhật trạng thái cảnh báo
    $stmt = $conn->prepare("SELECT outstanding_balance, warning_status, warning_start_date FROM customers WHERE customer_id = ?");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer['outstanding_balance'] > 2000 && $customer['warning_status'] == 0) {
        // Đặt cảnh báo nếu công nợ vượt quá 2000 USD
        $stmt = $conn->prepare("UPDATE customers SET warning_status = 1, warning_start_date = CURDATE() WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
    }

    if ($customer['warning_status'] == 1 && $customer['warning_start_date'] && (strtotime($customer['warning_start_date']) <= strtotime('-6 months'))) {
        // Đánh dấu "nợ xấu" nếu cảnh báo kéo dài quá 6 tháng
        $stmt = $conn->prepare("UPDATE customers SET bad_debt_status = 1 WHERE customer_id = ?");
        $stmt->execute([$customer_id]);
    }
}
?>
