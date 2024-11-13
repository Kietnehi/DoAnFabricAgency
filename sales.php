<?php
require 'connect.php';

// Lấy danh sách khách hàng và nhân viên
$customers = $conn->query("SELECT customer_id, first_name, last_name FROM Customers")->fetchAll(PDO::FETCH_ASSOC);
$employees = $conn->query("SELECT employee_id, first_name, last_name FROM Employees")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách các cuộn vải có trong kho
$fabric_rolls = $conn->query("SELECT Fabric_Rolls.roll_id, Fabric_Types.name AS fabric_name, Fabric_Rolls.length 
                              FROM Fabric_Rolls 
                              JOIN Fabric_Types ON Fabric_Rolls.fabric_type_id = Fabric_Types.fabric_type_id")->fetchAll(PDO::FETCH_ASSOC);
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
                <option value="<?= $customer['customer_id']; ?>">
                    <?= $customer['first_name'] . " " . $customer['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Chọn nhân viên bán hàng -->
        <label for="employee_id">Nhân Viên:</label>
        <select name="employee_id" required>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee['employee_id']; ?>">
                    <?= $employee['first_name'] . " " . $employee['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Chọn các cuộn vải -->
        <h2>Chọn Cuộn Vải</h2>
        <?php foreach ($fabric_rolls as $roll): ?>
            <input type="checkbox" name="fabric_rolls[]" value="<?= $roll['roll_id']; ?>">
            <?= $roll['fabric_name']; ?> - Dài: <?= $roll['length']; ?> mét <br>
        <?php endforeach; ?>

        <label for="total_amount">Tổng Tiền:</label>
        <input type="number" name="total_amount" step="0.01" required>

        <button type="submit">Tạo Đơn Hàng</button>
    </form>
</body>
</html>
