<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy danh sách khách hàng và nhân viên
$customers = $conn->query("SELECT customer_id, first_name, last_name FROM Customers")->fetchAll(PDO::FETCH_ASSOC);
$employees = $conn->query("SELECT employee_id, first_name, last_name FROM Employees")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách các cuộn vải và giá từ kho, bao gồm hình ảnh
$fabric_rolls = $conn->query("SELECT Fabric_Rolls.roll_id, Fabric_Types.name AS fabric_name, Fabric_Types.current_price, Fabric_Rolls.length, Fabric_Types.image 
                              FROM Fabric_Rolls 
                              JOIN Fabric_Types ON Fabric_Rolls.fabric_type_id = Fabric_Types.fabric_type_id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tạo Đơn Hàng Mới</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f9fc;
            margin: 0;
            padding: 0;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 100%;
            margin: 50px auto;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-top: 15px;
        }

        select, input[type="number"], button {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        select:focus, input[type="number"]:focus, button:hover {
            border-color: #007bff;
        }

        .product-options {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .product-item {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            flex: 1 1 45%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-item:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.15);
        }

        .product-item img {
            max-width: 80px;
            max-height: 80px;
            margin-right: 15px;
            border-radius: 4px;
        }

        .product-item .product-name {
            flex: 1;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .product-item .quantity {
            width: 60px;
            padding: 8px;
            font-size: 14px;
        }

        .total-display {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #ddd;
        }

        button {
            padding: 15px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .product-item {
                flex: 1 1 100%;
            }

            .product-options {
                justify-content: center;
            }
        }
    </style>
    <script>
        // Hàm tính tổng tiền theo USD
        function calculateTotal() {
            const productItems = document.querySelectorAll('.product-item');
            let totalAmount = 0;

            productItems.forEach(item => {
                const price = parseFloat(item.getAttribute('data-price'));
                const quantity = parseInt(item.querySelector('.quantity').value, 10);
                totalAmount += price * quantity;
            });

            document.getElementById('total_amount').value = totalAmount.toFixed(2);
            document.getElementById('total_display').innerText = `Tổng Tiền (USD): $${totalAmount.toFixed(2)}`;
        }
    </script>
</head>
<body>
    <div class="container">
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

            <!-- Chọn các sản phẩm (cuộn vải) -->
            <label>Chọn Cuộn Vải:</label>
            <div class="product-options">
                <?php foreach ($fabric_rolls as $roll): ?>
                    <div class="product-item" data-price="<?= $roll['current_price'] ?>">
                        <img src="img/<?= $roll['image']; ?>" alt="<?= $roll['fabric_name']; ?>">
                        <span class="product-name"><?= $roll['fabric_name']; ?> - Giá: $<?= $roll['current_price']; ?>/m - Dài: <?= $roll['length']; ?> mét</span>
                        <input type="number" class="quantity" name="quantity[<?= $roll['roll_id']; ?>]" value="1" min="1" onchange="calculateTotal()">
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Tổng tiền hiển thị -->
            <div class="total-display" id="total_display">Tổng Tiền (USD): $0.00</div>
            <input type="hidden" id="total_amount" name="total_amount" step="0.01" required readonly>

            <!-- Nút tạo đơn hàng -->
            <button type="submit">Tạo Đơn Hàng</button>
        </form>
    </div>
</body>
</html>
