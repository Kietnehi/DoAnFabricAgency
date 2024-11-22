<?php
require 'connect.php';

// Lấy danh sách khách hàng và nhân viên
$customers = $conn->query("SELECT CusId, Fname, Lname FROM customer")->fetchAll(PDO::FETCH_ASSOC);
$employees = $conn->query("SELECT ECode, Fname, Lname FROM employee")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách các cuộn vải có trong kho
$fabric_bolts = $conn->query("
    SELECT bolt.BCode, category.Name AS fabric_name, bolt.Length 
    FROM bolt 
    JOIN category ON bolt.CCode = category.CCode
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo Đơn Hàng Mới</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Tạo Đơn Hàng Bán Hàng</h1>
    <form action="process_sale.php" method="POST">
        <!-- Chọn khách hàng -->
        <label for="customer_id">Khách Hàng:</label>
        <select name="customer_id" required>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['CusId']; ?>">
                    <?= $customer['Fname'] . " " . $customer['Lname']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Chọn nhân viên bán hàng -->
        <label for="employee_id">Nhân Viên:</label>
        <select name="employee_id" required>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee['ECode']; ?>">
                    <?= $employee['Fname'] . " " . $employee['Lname']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Chọn các cuộn vải -->
        <h2>Chọn Cuộn Vải</h2>
        <?php foreach ($fabric_bolts as $bolt): ?>
            <input type="checkbox" name="fabric_bolts[]" value="<?= $bolt['BCode']; ?>">
            <?= $bolt['fabric_name']; ?> - Dài: <?= $bolt['Length']; ?> mét <br>
        <?php endforeach; ?>

        <label for="total_amount">Tổng Tiền:</label>
        <input type="number" name="total_amount" step="0.01" required>

        <button type="submit">Tạo Đơn Hàng</button>
    </form>
</body>
</html>
