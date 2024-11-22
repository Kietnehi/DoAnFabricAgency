<?php
require 'connect.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

$history_stmt = $conn->prepare("SELECT PaymentTime, Amount FROM customer_partialpayments WHERE OCode = :order_id ORDER BY PaymentTime DESC");
$history_stmt->execute(['order_id' => $order_id]);
$history = $history_stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($history);
?>
