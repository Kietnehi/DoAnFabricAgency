<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy danh sách khách hàng và nhân viên
$customers = $conn->query("SELECT CusId, Fname, Lname FROM customer")->fetchAll(PDO::FETCH_ASSOC);
$employees = $conn->query("SELECT ECode, Fname, Lname FROM employee")->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách các cuộn vải và thông tin liên quan
$fabric_rolls = $conn->query("
    SELECT 
        bolt.BCode, 
        category.Name AS fabric_name, 
        category.Price AS current_price, 
        bolt.Length AS roll_length, 
        category.RemainQuantity AS total_inventory_quantity, 
        category.img AS image 
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
        function calculateTotal() {
            const productItems = document.querySelectorAll('.product-item');
            let totalAmount = 0;

            productItems.forEach(item => {
                const price = parseFloat(item.getAttribute('data-price'));
                const quantityInput = item.querySelector('.quantity');
                let quantity = parseInt(quantityInput.value, 10);
                const availableInventory = parseFloat(item.getAttribute('data-inventory'));

                if (quantity > availableInventory) {
                    alert(`Số lượng yêu cầu cho ${item.querySelector('.product-name').innerText} vượt quá tồn kho (${availableInventory} còn lại).`);
                    quantity = availableInventory;
                    quantityInput.value = availableInventory;
                }

                totalAmount += price * quantity;
            });

            document.getElementById('total_amount').value = totalAmount.toFixed(2);
            document.getElementById('total_display').innerText = `Tổng Tiền (VND): ${totalAmount.toFixed(2)}`;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Tạo Đơn Hàng Mới</h1>
        <form action="process_order.php" method="POST">
            <label for="customer_id">Khách Hàng:</label>
            <select name="customer_id" required>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= $customer['CusId']; ?>">
                        <?= htmlspecialchars($customer['Fname'] . " " . $customer['Lname']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="employee_id">Nhân Viên:</label>
            <select name="employee_id" required>
                <?php foreach ($employees as $employee): ?>
                    <option value="<?= $employee['ECode']; ?>">
                        <?= htmlspecialchars($employee['Fname'] . " " . $employee['Lname']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Chọn Cuộn Vải:</label>
            <div class="product-options">
                <?php foreach ($fabric_rolls as $roll): ?>
                    <div class="product-item" data-price="<?= $roll['current_price'] ?>" data-inventory="<?= $roll['total_inventory_quantity'] ?>">
                        <img src="img/<?= htmlspecialchars($roll['image']); ?>" alt="<?= htmlspecialchars($roll['fabric_name']); ?>">
                        <span class="product-name">
                            <?= htmlspecialchars($roll['fabric_name']); ?> - Giá: <?= number_format($roll['current_price'], 2); ?> VND/m -
                            Dài: <?= number_format($roll['roll_length'], 2); ?> mét - 
                            Tồn kho: <?= $roll['total_inventory_quantity']; ?> đơn vị
                        </span>
                        <input type="number" class="quantity" name="quantity[<?= $roll['BCode']; ?>]" value="0" min="0" onchange="calculateTotal()">
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="total-display" id="total_display">Tổng Tiền (VND): 0.00</div>
            <input type="hidden" id="total_amount" name="total_amount" step="0.01" required readonly>

            <button type="submit">Tạo Đơn Hàng</button>
        </form>
    </div>
</body>
</html>