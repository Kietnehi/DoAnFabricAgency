<?php
require 'connect.php';

function updateCustomerBalance($conn, $customer_id, $payment_amount) {
    // Update the outstanding balance of the customer
    $stmt = $conn->prepare("UPDATE customer SET Dept = Dept - ? WHERE CusId = ?");
    $stmt->execute([$payment_amount, $customer_id]);

    // Fetch the updated balance and alert status
    $stmt = $conn->prepare("
        SELECT Dept AS outstanding_balance, 
               cs.Alert AS warning_status, 
               cs.AlertStartDate AS warning_start_date
        FROM customer c
        LEFT JOIN customerstatus cs ON c.CusId = cs.CusId
        WHERE c.CusId = ?
    ");
    $stmt->execute([$customer_id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($customer['outstanding_balance'] > 2000 && $customer['warning_status'] == 0) {
        // Set warning if the outstanding balance exceeds 2000 USD
        $stmt = $conn->prepare("UPDATE customerstatus SET Alert = 1, AlertStartDate = CURDATE() WHERE CusId = ?");
        $stmt->execute([$customer_id]);
    }

    if ($customer['warning_status'] == 1 && $customer['warning_start_date'] && 
        (strtotime($customer['warning_start_date']) <= strtotime('-6 months'))) {
        // Mark as "bad debt" if the warning has been active for more than 6 months
        $stmt = $conn->prepare("UPDATE customerstatus SET BadDebt = 1 WHERE CusId = ?");
        $stmt->execute([$customer_id]);
    }
}
?>
