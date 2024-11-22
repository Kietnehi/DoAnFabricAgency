<?php
ob_start(); // Bắt đầu bộ đệm output để tránh lỗi header
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy thông tin sản phẩm cần chỉnh sửa
$product = null;
if (isset($_GET['edit'])) {
    $CCode = $_GET['edit'];

    // Truy vấn sản phẩm từ bảng `category`
    $sql = "SELECT * FROM category WHERE CCode = :CCode";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':CCode' => $CCode]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Nếu không tìm thấy sản phẩm, đặt giá trị mặc định
if (!$product) {
    $product = [
        'CCode' => '',
        'Name' => '',
        'Color' => '',
        'Price' => '',
        'AppliedDate' => '',
        'RemainQuantity' => '',
        'SCode' => '',
        'img' => ''
    ];
}

// Xử lý cập nhật sản phẩm
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $applied_date = $_POST['applied_date'];
    $remain_quantity = $_POST['remain_quantity'];
    $SCode = $_POST['SCode'];
    $CCode = $_POST['CCode'];

    // Xử lý hình ảnh (nếu có tải lên)
    if (isset($_FILES['img']) && $_FILES['img']['error'] === 0) {
        $imgName = $_FILES['img']['name'];
        $imgTmpName = $_FILES['img']['tmp_name'];
        $imgPath = 'img/' . $imgName;

        // Di chuyển tệp vào thư mục "img"
        move_uploaded_file($imgTmpName, $imgPath);
    } else {
        // Giữ lại hình ảnh cũ nếu không tải hình ảnh mới
        $imgName = $product['img'];
    }

    try {
        // Cập nhật sản phẩm vào cơ sở dữ liệu
        $sql = "UPDATE category 
                SET Name = :name, Color = :color, Price = :price, AppliedDate = :applied_date, 
                    RemainQuantity = :remain_quantity, SCode = :SCode, img = :img 
                WHERE CCode = :CCode";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':color' => $color,
            ':price' => $price,
            ':applied_date' => $applied_date,
            ':remain_quantity' => $remain_quantity,
            ':SCode' => $SCode,
            ':img' => $imgName,
            ':CCode' => $CCode
        ]);

        // Chuyển hướng sau khi cập nhật thành công
        header('Location: product_manager.php');
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
ob_end_flush(); // Kết thúc bộ đệm output
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="edit_product.css">
    <title>Sửa sản phẩm</title>
    <style>
        .image-preview {
            margin-top: 10px;
            text-align: center;
        }
        .image-preview img {
            width: 150px;
            height: auto;
            border-radius: 5px;
            margin: 10px auto;
            border: 1px solid #ddd;
            padding: 5px;
            display: block;
        }
    </style>
</head>
<body>
<h1>Sửa sản phẩm</h1>

<form action="edit_product.php?edit=<?php echo htmlspecialchars($product['CCode']); ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="CCode" value="<?php echo htmlspecialchars($product['CCode']); ?>">

    <label for="name">Tên sản phẩm:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['Name']); ?>" required><br>

    <label for="color">Màu sắc:</label>
    <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($product['Color']); ?>" required><br>

    <label for="price">Giá hiện tại:</label>
    <input type="number" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($product['Price']); ?>" required><br>

    <label for="applied_date">Ngày hiệu lực:</label>
    <input type="date" id="applied_date" name="applied_date" value="<?php echo htmlspecialchars($product['AppliedDate']); ?>" required><br>

    <label for="remain_quantity">Số lượng còn lại:</label>
    <input type="number" id="remain_quantity" name="remain_quantity" value="<?php echo htmlspecialchars($product['RemainQuantity']); ?>" required><br>

    <label for="SCode">Mã nhà cung cấp:</label>
    <input type="number" id="SCode" name="SCode" value="<?php echo htmlspecialchars($product['SCode']); ?>" required><br>

    <label for="img">Hình ảnh:</label>
    <div class="image-preview">
        <!-- Hiển thị hình ảnh cũ -->
        <?php if ($product['img']): ?>
            <p>Hình ảnh cũ:</p>
            <img src="img/<?php echo htmlspecialchars($product['img']); ?>" alt="Hình ảnh cũ" id="oldImage">
        <?php else: ?>
            <p>Chưa có hình ảnh</p>
        <?php endif; ?>

        <!-- Vùng để hiển thị hình ảnh mới -->
        <p>Hình ảnh mới:</p>
        <img id="newImage" style="display: none;">
    </div>
    <input type="file" id="img" name="img" accept="image/*" onchange="previewNewImage(event)"><br><br>

    <button type="submit" name="update">Cập nhật</button>
</form>

<script>
    // Hàm xem trước hình ảnh mới
    function previewNewImage(event) {
        const file = event.target.files[0];
        const newImage = document.getElementById('newImage');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                newImage.src = e.target.result;
                newImage.style.display = 'block'; // Hiển thị hình ảnh mới
            };
            reader.readAsDataURL(file); // Đọc dữ liệu hình ảnh
        } else {
            newImage.style.display = 'none'; // Ẩn hình ảnh nếu không có tệp
        }
    }
</script>

</body>
</html>
