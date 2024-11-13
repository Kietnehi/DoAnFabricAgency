<?php
ob_start(); // Bắt đầu bộ đệm output để tránh lỗi header
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Kiểm tra xem có tham số 'edit' trong URL hay không
$product = null;
if (isset($_GET['edit'])) {
    $fabric_type_id = $_GET['edit'];

    // Lấy thông tin sản phẩm từ DB
    $sql = "SELECT * FROM fabric_types WHERE fabric_type_id = :fabric_type_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':fabric_type_id' => $fabric_type_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Nếu không tìm thấy sản phẩm, tạo giá trị mặc định cho $product để tránh lỗi undefined variable
if (!$product) {
    $product = [
        'fabric_type_id' => '',
        'name' => '',
        'color' => '',
        'current_price' => '',
        'price_effective_date' => '',
        'quantity' => '',
        'supplier_id' => '',
        'image' => ''
    ];
}

// Cập nhật sản phẩm
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $color = $_POST['color'];
    $current_price = $_POST['current_price'];
    $price_effective_date = $_POST['price_effective_date'];
    $quantity = $_POST['quantity'];
    $supplier_id = $_POST['supplier_id'];
    $fabric_type_id = $_POST['fabric_type_id'];

    // Kiểm tra và xử lý hình ảnh (lưu trữ hình ảnh vào thư mục img và chỉ lưu tên tệp)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imagePath = 'img/' . $imageName; // Đường dẫn nơi lưu hình ảnh trên server

        // Di chuyển hình ảnh vào thư mục img
        move_uploaded_file($imageTmpName, $imagePath);

        // Sử dụng tên hình ảnh đã lưu trong cơ sở dữ liệu
        $imageBase64 = $imageName;
    } else {
        // Nếu không có hình ảnh mới, giữ nguyên hình ảnh cũ
        $imageBase64 = $product['image'];
    }

    // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu
    $sql = "UPDATE fabric_types SET name = :name, color = :color, current_price = :current_price,
            price_effective_date = :price_effective_date, quantity = :quantity, supplier_id = :supplier_id, image = :image
            WHERE fabric_type_id = :fabric_type_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':color' => $color,
        ':current_price' => $current_price,
        ':price_effective_date' => $price_effective_date,
        ':quantity' => $quantity,
        ':supplier_id' => $supplier_id,
        ':image' => $imageBase64,
        ':fabric_type_id' => $fabric_type_id
    ]);

    // Chuyển hướng về trang danh sách sản phẩm sau khi cập nhật
    header('Location: product_manager.php');
    exit();
}
ob_end_flush(); // Kết thúc bộ đệm output
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sản phẩm</title>
    
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        form {
            width: 50%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="file"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .image-preview {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview img {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .image-preview img:hover {
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>

</head>
<body>

<h1>Sửa sản phẩm</h1>

<form action="edit_product.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="fabric_type_id" value="<?php echo htmlspecialchars($product['fabric_type_id']); ?>">

    <label for="name">Tên sản phẩm:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>

    <label for="color">Màu sắc:</label>
    <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($product['color']); ?>" required><br>

    <label for="current_price">Giá hiện tại:</label>
    <input type="number" id="current_price" name="current_price" value="<?php echo htmlspecialchars($product['current_price']); ?>" required><br>

    <label for="price_effective_date">Ngày hiệu lực:</label>
    <input type="date" id="price_effective_date" name="price_effective_date" value="<?php echo htmlspecialchars($product['price_effective_date']); ?>" required><br>

    <label for="quantity">Số lượng:</label>
    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required><br>

    <label for="supplier_id">Nhà cung cấp:</label>
    <input type="text" id="supplier_id" name="supplier_id" value="<?php echo htmlspecialchars($product['supplier_id']); ?>" required><br>

    <label for="image">Hình ảnh:</label>
    <!-- Hiển thị hình ảnh cũ nếu có -->
    <?php if ($product['image']): ?>
        <div class="image-preview">
            <p>Hình ảnh cũ:</p>
            <img src="img/<?php echo htmlspecialchars($product['image']); ?>" alt="Hình ảnh cũ">
        </div>
    <?php else: ?>
        <p>Chưa có hình ảnh</p>
    <?php endif; ?>

    <!-- Cho phép người dùng chọn hình ảnh mới -->
    <input type="file" name="image"><br><br>

    <button type="submit" name="update">Cập nhật</button>
</form>

</body>
</html>
