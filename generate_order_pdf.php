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
    SELECT orders.OCode AS order_id, orders.OrderTime AS order_date, orders.TotalPrice AS total_amount, 
           orders.Status AS status, customer.Fname AS customer_first_name, customer.Lname AS customer_last_name
    FROM orders
    JOIN customer ON orders.CusId = customer.CusId
    WHERE orders.OCode = ?
");
$order_stmt->execute([$order_id]);
$order = $order_stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}

// Lấy chi tiết sản phẩm của đơn hàng
$product_stmt = $conn->prepare("
    SELECT c.Name AS fabric_name, b.Length AS length, od.Quantity AS quantity,
           od.UnitPrice AS unit_price, od.TotalPrice AS total_price
    FROM order_detail od
    JOIN bolt b ON od.BCode = b.BCode
    JOIN category c ON b.CCode = c.CCode
    WHERE od.OCode = ?
");
$product_stmt->execute([$order_id]);
$products = $product_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy chi tiết thanh toán của đơn hàng (chỉ lấy thanh toán cho đơn hàng này)
$payment_stmt = $conn->prepare("
    SELECT PaymentTime AS payment_date, Amount AS amount
    FROM customer_partialpayments
    WHERE OCode = ?  -- Chỉ lấy thanh toán cho đơn hàng này
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
$pdf->Ln(10);

// Chi tiết sản phẩm
$pdf->SetTextColor(33, 37, 41);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'Chi Tiết Sản Phẩm', 0, 1);
$pdf->Ln(5);

// Tiêu đề bảng
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(60, 8, 'Tên Vải', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Chiều Dài (m)', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Số Lượng', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Đơn Giá (USD)', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Tổng Giá (USD)', 1, 1, 'C', true);

// Dữ liệu bảng chi tiết sản phẩm
$pdf->SetFont('dejavusans', '', 10);
foreach ($products as $product) {
    $pdf->Cell(60, 8, $product['fabric_name'], 1, 0, 'L');
    $pdf->Cell(30, 8, $product['length'] . ' m', 1, 0, 'R');
    $pdf->Cell(30, 8, $product['quantity'], 1, 0, 'R');
    $pdf->Cell(30, 8, '$' . number_format($product['unit_price'], 2), 1, 0, 'R');
    $pdf->Cell(30, 8, '$' . number_format($product['total_price'], 2), 1, 1, 'R');
}
$pdf->Ln(10);

// Chi tiết thanh toán
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'Chi Tiết Thanh Toán', 0, 1);
$pdf->Ln(5);

// Tiêu đề bảng thanh toán
$pdf->SetFont('dejavusans', 'B', 10);
$pdf->SetFillColor(220, 220, 220);
$pdf->Cell(60, 8, 'Ngày Thanh Toán', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Số Tiền (USD)', 1, 1, 'C', true);

// Dữ liệu bảng thanh toán
$pdf->SetFont('dejavusans', '', 10);
foreach ($payments as $payment) {
    $pdf->Cell(60, 8, $payment['payment_date'], 1, 0, 'C');
    $pdf->Cell(60, 8, '$' . number_format($payment['amount'], 2), 1, 1, 'R');
}

// Xuất PDF, tải xuống trực tiếp
$pdf->Output('ChiTietDonHang_' . $order['order_id'] . '.pdf', 'D');
?>
