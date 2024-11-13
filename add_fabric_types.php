<?php
include "connect.php"; // Kết nối cơ sở dữ liệu
include "nav.php"; // Bao gồm thanh điều hướng nếu có

$successMessage = ""; // Biến lưu thông báo thành công

// Kiểm tra nếu form đã được gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $fabricName = $_POST['fabric_name'];
    $fabricColor = $_POST['fabric_color'];
    $fabricPrice = $_POST['fabric_price'];
    $fabricQuantity = $_POST['fabric_quantity'];
    $supplierId = $_POST['supplier_id'];
    $priceEffectiveDate = $_POST['price_effective_date']; // Ngày hiệu lực giá

    // Xử lý hình ảnh
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $uploadDir = 'img/'; // Thư mục lưu trữ hình ảnh
        $imageName = basename($_FILES['product_image']['name']);
        $uploadFile = $uploadDir . $imageName;
        $uploadFile2 = $imageName;

        // Kiểm tra loại tệp
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $imagePath = $uploadFile2;
            } else {
                $imagePath = ''; // Nếu không tải được hình ảnh, để trống
            }   
        } else {
            $imagePath = ''; // Nếu không đúng định dạng
        }
    } else {
        $imagePath = ''; // Nếu không có hình ảnh
    }

    // Kiểm tra và chèn vào cơ sở dữ liệu
    if ($fabricName && $fabricPrice && $fabricQuantity && $supplierId) {
        try {
            // Thêm dữ liệu vào bảng fabric_types
            $stmt = $conn->prepare("INSERT INTO fabric_types (name, color, current_price, price_effective_date, quantity, supplier_id, image) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$fabricName, $fabricColor, $fabricPrice, $priceEffectiveDate, $fabricQuantity, $supplierId, $imagePath]);
            
            // Lấy ID của loại vải vừa được thêm
            $fabricTypeId = $conn->lastInsertId();

            // Thêm cuộn vải vào bảng fabric_rolls
            if (isset($_POST['roll_lengths'])) {
                foreach ($_POST['roll_lengths'] as $rollLength) {
                    if (is_numeric($rollLength) && $rollLength > 0) {
                        $stmt = $conn->prepare("INSERT INTO fabric_rolls (fabric_type_id, length) VALUES (?, ?)");
                        $stmt->execute([$fabricTypeId, $rollLength]);
                    }
                }
            }

            $successMessage = "Sản phẩm và các cuộn vải đã được thêm thành công!";
        } catch (PDOException $e) {
            echo "<p>Lỗi khi thêm sản phẩm: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Vui lòng điền đầy đủ thông tin sản phẩm.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm</title>
    <style>
        /* Style chính */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.6s ease-in-out;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], 
        input[type="number"], 
        input[type="date"], 
        select, 
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus, 
        input[type="number"]:focus, 
        input[type="date"]:focus, 
        select:focus, 
        input[type="file"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        .rolls-container {
            margin-top: 20px;
        }

        .rolls-container input {
            width: 80%;
            display: inline-block;
            margin-bottom: 8px;
        }

        .add-roll-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-bottom: 15px;
        }

        .image-preview {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .image-preview img:hover {
            transform: scale(1.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Success message style */
        .success-message {
            display: none;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($successMessage): ?>
        <div class="success-message" id="successMessage"><?= $successMessage ?></div>
    <?php endif; ?>

    <h1>Thêm Sản Phẩm Mới</h1>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="fabric_name">Tên Loại Vải:</label>
        <input type="text" id="fabric_name" name="fabric_name" required><br>

        <label for="fabric_color">Màu Sắc:</label>
        <input type="text" id="fabric_color" name="fabric_color"><br>

        <label for="fabric_price">Giá:</label>
        <input type="number" id="fabric_price" name="fabric_price" step="0.01" required><br>

        <label for="price_effective_date">Ngày Có Hiệu Lực:</label>
        <input type="date" id="price_effective_date" name="price_effective_date" required><br>

        <label for="fabric_quantity">Số Lượng:</label>
        <input type="number" id="fabric_quantity" name="fabric_quantity" required><br>

        <label for="supplier_id">Nhà Cung Cấp:</label>
        <select id="supplier_id" name="supplier_id" required>
            <?php
            $stmt = $conn->query("SELECT supplier_id, name FROM suppliers");
            while ($supplier = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $supplier['supplier_id'] . "'>" . $supplier['name'] . "</option>";
            }
            ?>
        </select><br>

        <label for="product_image">Hình Ảnh Sản Phẩm:</label>
        <input type="file" id="product_image" name="product_image" accept="image/*" onchange="previewImage(event)"><br><br>

        <div class="image-preview">
            <img id="imagePreview" src="" alt="Hình ảnh sản phẩm sẽ xuất hiện ở đây">
        </div>

        <div class="rolls-container">
            <label for="roll_lengths">Chiều Dài Cuộn Vải:</label>
            <input type="number" step="0.01" name="roll_lengths[]" required>
            <button type="button" class="add-roll-btn" onclick="addRollInput()">Thêm cuộn vải khác</button>
        </div>

        <button type="submit">Thêm Sản Phẩm</button>
    </form>
</div>

<script>
    // Chức năng xem trước hình ảnh khi chọn tệp
    function previewImage(event) {
        const file = event.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        }
        
        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // Thêm ô nhập chiều dài cuộn vải
    function addRollInput() {
        const container = document.querySelector('.rolls-container');
        const newInput = document.createElement('input');
        newInput.type = 'number';
        newInput.step = '0.01';
        newInput.name = 'roll_lengths[]';
        newInput.required = true;
        container.insertBefore(newInput, container.lastElementChild);
    }

    // Hiển thị thông báo thành công với hiệu ứng
    window.addEventListener("load", function() {
        const successMessage = document.getElementById("successMessage");
        if (successMessage) {
            successMessage.style.display = "block";
            setTimeout(() => {
                successMessage.style.display = "none";
            }, 3000); // Ẩn thông báo sau 3 giây
        }
    });
</script>

</body>
</html>
