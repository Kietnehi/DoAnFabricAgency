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
    <link rel="stylesheet" href="supplier_details.css">
    <title>Chi tiết nhà cung cấp</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        img {
            border-radius: 5px;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: gray;
        }
    </style>
</head>
<body>
    <h2>Chi tiết nhà cung cấp: <?= htmlspecialchars($supplier['Name']); ?></h2>
    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($supplier['Address']); ?></p>
    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($supplier['Phone']); ?></p>
    <p><strong>Mã số thuế:</strong> <?= htmlspecialchars($supplier['TaxCode']); ?></p>
    <p><strong>Tài khoản ngân hàng:</strong> <?= htmlspecialchars($supplier['BankAccount']); ?></p>

    <p><strong>Nhân viên đối tác:</strong>
    <?php if (count($partner_staff) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Mã nhân viên</th><?= htmlspecialchars($staff['ECode']); ?>
                    <th>Họ và tên</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($partner_staff as $staff): ?>
                <tr>
                    <td></td>
                    <td><?= htmlspecialchars($staff['Fname'] . " " . $staff['Lname']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h3>Các sản phẩm được cung cấp</h3>
    <?php if (count($products) > 0): ?>
        <table>
            <thead>
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
    <?php else: ?>
        <p class="no-data">Nhà cung cấp này chưa cung cấp sản phẩm nào.</p>
    <?php endif; ?>

    <a href="product_manager.php" class="btn btn-primary">Quay lại</a>
</body>
</html>
