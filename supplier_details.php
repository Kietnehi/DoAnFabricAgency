<?php
include 'connect.php'; // Kết nối với cơ sở dữ liệu
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy mã nhà cung cấp từ URL
$SCode = isset($_GET['SCode']) && is_numeric($_GET['SCode']) ? (int)$_GET['SCode'] : 0;

// Lấy thông tin nhà cung cấp
$supplier_stmt = $conn->prepare("SELECT * FROM supplier WHERE SCode = ?");
$supplier_stmt->execute([$SCode]);
$supplier = $supplier_stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    echo "<h2>Không tìm thấy thông tin nhà cung cấp!</h2>";
    exit();
}

// Lấy danh sách sản phẩm do nhà cung cấp cung cấp
$products_stmt = $conn->prepare("
    SELECT c.CCode, c.Name AS ProductName, c.Color, c.Price, c.AppliedDate, c.RemainQuantity, c.img 
    FROM category c 
    WHERE c.SCode = ?
");
$products_stmt->execute([$SCode]);
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách nhân viên đối tác ("PartnerStaff") liên quan đến nhà cung cấp
$staff_stmt = $conn->prepare("
    SELECT e.ECode, e.Fname, e.Lname
    FROM employee e
    JOIN supplier s ON e.ECode = s.ECode
    WHERE e.Role = 'PartnerStaff' AND s.SCode = ?
");
$staff_stmt->execute([$SCode]);
$partner_staff = $staff_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Chi tiết nhà cung cấp</title>
    <style>
        .no-data {
            text-align: center;
            font-style: italic;
            color: gray;
        }
        .supplier-details {
            margin-top: 20px;
        }
        .product-table img {
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container supplier-details">
        <h2 class="text-center mb-4">Chi tiết nhà cung cấp: <?= htmlspecialchars($supplier['Name']); ?></h2>
        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($supplier['Address']); ?></p>
                <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($supplier['Phone']); ?></p>
                <p><strong>Mã số thuế:</strong> <?= htmlspecialchars($supplier['TaxCode']); ?></p>
                <p><strong>Tài khoản ngân hàng:</strong> <?= htmlspecialchars($supplier['BankAccount']); ?></p>
                <?php if (isset($partner_staff[0])): ?>
                <p><strong>Nhân viên đối tác:</strong> NV <?= htmlspecialchars($partner_staff[0]['ECode']); ?> - <?= htmlspecialchars($partner_staff[0]['Fname'] . " " . $partner_staff[0]['Lname']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <h3 class="mb-3">Các sản phẩm được cung cấp</h3>
        <?php if (count($products) > 0): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover product-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Màu sắc</th>
                            <th>Giá</th>
                            <th>Ngày áp dụng</th>
                            <th>Số lượng</th>
                            <th>Hình ảnh</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['CCode']); ?></td>
                            <td><?= htmlspecialchars($product['ProductName']); ?></td>
                            <td><?= htmlspecialchars($product['Color']); ?></td>
                            <td><?= number_format($product['Price'], 2); ?> USD</td>
                            <td><?= htmlspecialchars($product['AppliedDate']); ?></td>
                            <td><?= htmlspecialchars($product['RemainQuantity']); ?></td>
                            <td>
                                <?php if ($product['img']): ?>
                                    <img src="img/<?= htmlspecialchars($product['img']); ?>" alt="Hình ảnh sản phẩm" width="50">
                                <?php else: ?>
                                    <p class="no-data">Chưa có hình ảnh</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="no-data">Nhà cung cấp này chưa cung cấp sản phẩm nào.</p>
        <?php endif; ?>

        <a href="product_manager.php" class="btn btn-primary mt-3"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>