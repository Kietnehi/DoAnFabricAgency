<?php
include 'connect.php'; // Kết nối với cơ sở dữ liệu

// Hàm xóa sản phẩm
if (isset($_GET['delete'])) {
    $fabric_type_id = $_GET['delete'];

    try {
        // Bắt đầu giao dịch
        $conn->beginTransaction();

        // Xóa các bản ghi trong `order_fabric_rolls` liên quan đến `fabric_rolls` có `fabric_type_id` tương ứng
        $sql = "DELETE order_fabric_rolls FROM order_fabric_rolls 
                INNER JOIN fabric_rolls ON order_fabric_rolls.roll_id = fabric_rolls.roll_id 
                WHERE fabric_rolls.fabric_type_id = :fabric_type_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':fabric_type_id' => $fabric_type_id]);

        // Xóa các bản ghi trong `fabric_rolls` liên quan đến `fabric_type_id`
        $sql = "DELETE FROM fabric_rolls WHERE fabric_type_id = :fabric_type_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':fabric_type_id' => $fabric_type_id]);

        // Xóa sản phẩm trong bảng `fabric_types`
        $sql = "DELETE FROM fabric_types WHERE fabric_type_id = :fabric_type_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':fabric_type_id' => $fabric_type_id]);

        // Xác nhận giao dịch
        $conn->commit();

        // Chuyển hướng sau khi xóa thành công
        header('Location: product_manager.php');
        exit;

    } catch (PDOException $e) {
        // Hủy giao dịch nếu có lỗi
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

// Hàm thêm sản phẩm
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $color = $_POST['color'];
    $current_price = $_POST['current_price'];
    $price_effective_date = $_POST['price_effective_date'];
    $quantity = $_POST['quantity'];
    $supplier_id = $_POST['supplier_id'];
    
    // Xử lý hình ảnh (chuyển hình ảnh thành base64)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
        $imageBase64 = base64_encode($imageData);
    } else {
        $imageBase64 = null;
    }

    // Thêm sản phẩm mới vào cơ sở dữ liệu
    $sql = "INSERT INTO fabric_types (name, color, current_price, price_effective_date, quantity, supplier_id, image) 
            VALUES (:name, :color, :current_price, :price_effective_date, :quantity, :supplier_id, :image)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':color' => $color,
        ':current_price' => $current_price,
        ':price_effective_date' => $price_effective_date,
        ':quantity' => $quantity,
        ':supplier_id' => $supplier_id,
        ':image' => $imageBase64
    ]);
}

include 'nav.php';

// Hàm sửa sản phẩm
if (isset($_GET['edit'])) {
    $fabric_type_id = $_GET['edit'];
    $sql = "SELECT * FROM fabric_types WHERE fabric_type_id = :fabric_type_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':fabric_type_id' => $fabric_type_id]);
    $product = $stmt->fetch();
}

// Hiển thị danh sách sản phẩm
$sql = "SELECT * FROM fabric_types";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    
    <style>
        /* Các kiểu dáng CSS cho giao diện */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        th, td {
            padding: 10px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
            transition: all 0.3s ease-in-out;
        }

        img {
            border-radius: 5px;
            transition: transform 0.2s ease-in-out;
        }

        img:hover {
            transform: scale(1.2);
        }

        a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #0056b3;
            font-weight: bold;
        }

        .btn {
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>

    <script type="text/javascript">
        // Hàm xác nhận khi xóa sản phẩm
        function confirmDelete(url) {
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
                window.location.href = url;
            }
        }
    </script>

</head>
<body>

<!-- Hiển thị danh sách sản phẩm -->
<h2>Danh sách sản phẩm</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Màu sắc</th>
            <th>Giá hiện tại</th>
            <th>Ngày hiệu lực</th>
            <th>Số lượng</th>
            <th>Nhà cung cấp</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?php echo htmlspecialchars($product['fabric_type_id']); ?></td>
            <td><?php echo htmlspecialchars($product['name']); ?></td>
            <td><?php echo htmlspecialchars($product['color']); ?></td>
            <td><?php echo number_format($product['current_price'], 2); ?></td>
            <td><?php echo htmlspecialchars($product['price_effective_date']); ?></td>
            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
            <td><?php echo htmlspecialchars($product['supplier_id']); ?></td>
            <td>
                <?php if ($product['image']): ?>
                    <img src="img/<?php echo $product['image']; ?>" width="50" height="50">
                <?php else: ?>
                    <p>No image</p>
                <?php endif; ?>
            </td>
            <td>
                <a href="edit_product.php?edit=<?php echo $product['fabric_type_id']; ?>" class="btn">Sửa</a> |
                <a href="javascript:void(0);" onclick="confirmDelete('product_manager.php?delete=<?php echo $product['fabric_type_id']; ?>')" class="btn btn-danger">Xóa</a> |
                <a href="add_fabric_types.php" class="btn">Thêm sản phẩm</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
