<?php
// Start output buffering
ob_start();

// Start session if it hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Kiểm tra nếu ID nhà cung cấp đã được truyền vào
if (!isset($_GET['id'])) {
    header("Location: supplier.php");
    exit();
}

$supplierId = $_GET['id'];

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $bank_account = $_POST['bank_account'];
    $tax_code = $_POST['tax_code'];
    $phone = $_POST['phone'];

    try {
        // Cập nhật thông tin nhà cung cấp
        $stmt = $conn->prepare("UPDATE supplier SET Name = ?, Address = ?, BankAccount = ?, TaxCode = ?, Phone = ? WHERE SCode = ?");
        $stmt->execute([$name, $address, $bank_account, $tax_code, $phone, $supplierId]);

        // Ghi thông báo thành công vào session
        $_SESSION['success_message'] = "Cập nhật thông tin nhà cung cấp thành công!";
        
        // Chuyển hướng về trang quản lý nhà cung cấp
        header("Location: supplier.php");
        exit();
    } catch (PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }
}

// Lấy thông tin nhà cung cấp hiện tại
$stmt = $conn->prepare("SELECT * FROM supplier WHERE SCode = ?");
$stmt->execute([$supplierId]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    echo "Nhà cung cấp không tồn tại.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa Nhà Cung Cấp</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Chỉnh sửa Nhà Cung Cấp</h2>

        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success text-center'>{$_SESSION['success_message']}</div>";
            unset($_SESSION['success_message']);
        }
        ?>

        <form action="edit_supplier.php?id=<?php echo $supplierId; ?>" method="POST">
            <div class="form-group">
                <label for="name">Tên Nhà Cung Cấp:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($supplier['Name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Địa Chỉ:</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($supplier['Address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="bank_account">Tài Khoản Ngân Hàng:</label>
                <input type="text" class="form-control" id="bank_account" name="bank_account" value="<?php echo htmlspecialchars($supplier['BankAccount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="tax_code">Mã Số Thuế:</label>
                <input type="text" class="form-control" id="tax_code" name="tax_code" value="<?php echo htmlspecialchars($supplier['TaxCode']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số Điện Thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($supplier['Phone']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn = null;

// End output buffering and flush output
ob_end_flush();
?>