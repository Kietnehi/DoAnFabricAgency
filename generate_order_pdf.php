<?php 
require 'connect.php';
require './tcpdf/tcpdf.php'; // Đảm bảo đường dẫn đúng đến TCPDF

if (!isset($_GET['order_id'])) {
    echo "Order ID is not specified.";
    exit();
}

$order_id = $_GET['order_id'];

// Lấy thông tin đơn hàng
$order_stmt = $conn->prepare("
    SELECT orders.order_id, orders.order_date, orders.total_amount, orders.status, orders.cancellation_reason,
           customers.first_name AS customer_first_name, customers.last_name AS customer_last_name
    FROM orders
    JOIN customers ON orders.customer_id = customers.customer_id
    WHERE orders.order_id = ?
");
$order_stmt->execute([$order_id]);
$order = $order_stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}

// Lấy chi tiết vải của đơn hàng
$roll_stmt = $conn->prepare("
    SELECT fabric_types.name AS fabric_name, fabric_types.color, fabric_rolls.length, fabric_types.current_price
    FROM order_fabric_rolls
    JOIN fabric_rolls ON order_fabric_rolls.roll_id = fabric_rolls.roll_id
    JOIN fabric_types ON fabric_rolls.fabric_type_id = fabric_types.fabric_type_id
    WHERE order_fabric_rolls.order_id = ?
");
$roll_stmt->execute([$order_id]);
$rolls = $roll_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy chi tiết thanh toán của đơn hàng
$payment_stmt = $conn->prepare("
    SELECT payment_date, amount
    FROM order_payments
    WHERE order_id = ?
");
$payment_stmt->execute([$order_id]);
$payments = $payment_stmt->fetchAll(PDO::FETCH_ASSOC);

// Khởi tạo đối tượng TCPDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company');
$pdf->SetTitle('Chi Tiết Đơn Hàng');
$pdf->SetSubject('Chi Tiết Đơn Hàng PDF');
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Thiết lập phông chữ Unicode
$pdf->SetFont('dejavusans', '', 12);

// Tiêu đề PDF
$pdf->SetFont('dejavusans', 'B', 16);
$pdf->SetTextColor(33, 37, 41);
$pdf->Cell(0, 10, 'Chi Tiết Đơn Hàng #' . $order['order_id'], 0, 1, 'C');
$pdf->Ln(10);

// Thông tin khách hàng
$pdf->SetFont('dejavusans', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 10, 'Khách Hàng: ' . $order['customer_first_name'] . ' ' . $order['customer_last_name'], 0, 1);
$pdf->Cell(0, 10, 'Ngày Đặt: ' . $order['order_date'], 0, 1);
$pdf->Cell(0, 10, 'Tổng Tiền: $' . number_format($order['total_amount'], 2), 0, 1);
$pdf->Cell(0, 10, 'Trạng Thái: ' . ucfirst($order['status']), 0, 1);
if ($order['status'] === 'cancelled') {
    $pdf->SetTextColor(220, 53, 69); // Đỏ nếu bị hủy
    $pdf->Cell(0, 10, 'Lý Do Hủy: ' . $order['cancellation_reason'], 0, 1);
}
$pdf->Ln(10);

// Chi tiết vải
$pdf->SetTextColor(33, 37, 41);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'Chi Tiết Vải', 0, 1);
$pdf->Ln(5);

// Tiêu đề bảng
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(50, 8, 'Tên Vải', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Màu Sắc', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Chiều Dài (m)', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Giá/m', 1, 1, 'C', true);

// Dữ liệu bảng chi tiết vải
$pdf->SetFont('dejavusans', '', 10);
$pdf->SetFillColor(255, 255, 255);
foreach ($rolls as $roll) {
    $pdf->Cell(50, 8, $roll['fabric_name'], 1, 0, 'L', true);
    $pdf->Cell(30, 8, $roll['color'], 1, 0, 'C', true);
    $pdf->Cell(30, 8, $roll['length'] . ' m', 1, 0, 'R', true);
    $pdf->Cell(30, 8, '$' . number_format($roll['current_price'], 2), 1, 1, 'R', true);
}
$pdf->Ln(10);

// Chi tiết thanh toán
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'Chi Tiết Thanh Toán', 0, 1);
$pdf->Ln(5);

// Tiêu đề bảng thanh toán
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(50, 8, 'Ngày Thanh Toán', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Số Tiền (USD)', 1, 1, 'C', true);

// Dữ liệu bảng thanh toán
$pdf->SetFont('dejavusans', '', 10);
foreach ($payments as $payment) {
    $pdf->Cell(50, 8, $payment['payment_date'], 1, 0, 'C');
    $pdf->Cell(50, 8, '$' . number_format($payment['amount'], 2), 1, 1, 'R');
}

// Xuất PDF, tải xuống trực tiếp
$pdf->Output('ChiTietDonHang_' . $order['order_id'] . '.pdf', 'D');
?>
