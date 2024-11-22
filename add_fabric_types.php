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
    $appliedDate = $_POST['applied_date'];

    // Xử lý hình ảnh
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $uploadDir = 'img/'; // Thư mục lưu trữ hình ảnh
        $imageName = basename($_FILES['product_image']['name']);
        $uploadFile = $uploadDir . $imageName;

        // Kiểm tra loại tệp
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if (in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadFile)) {
                $imagePath = $imageName; // Lưu tên tệp hình ảnh
            } else {
                $imagePath = null; // Nếu không tải được hình ảnh, để trống
            }
        } else {
            $imagePath = null; // Nếu không đúng định dạng
        }
    } else {
        $imagePath = null; // Nếu không có hình ảnh
    }

    // Kiểm tra và chèn vào cơ sở dữ liệu
    if ($fabricName && $fabricPrice && $fabricQuantity && $supplierId) {
        try {
            // Thêm dữ liệu vào bảng category
            $stmt = $conn->prepare("INSERT INTO category (Name, Color, Price, AppliedDate, RemainQuantity, SCode, img) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$fabricName, $fabricColor, $fabricPrice, $appliedDate, $fabricQuantity, $supplierId, $imagePath]);

            // Lấy CCode của sản phẩm vừa được thêm
            $CCode = $conn->lastInsertId();

            // Thêm các cuộn vải vào bảng bolt
            if (isset($_POST['roll_lengths'])) {
                foreach ($_POST['roll_lengths'] as $rollLength) {
                    if (is_numeric($rollLength) && $rollLength > 0) {
                        $stmt = $conn->prepare("INSERT INTO bolt (Length, CCode) VALUES (?, ?)");
                        $stmt->execute([$rollLength, $CCode]);
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
    <link rel="stylesheet" href="add_fabric_types.css">
</head>
<body>

<div class="container">
    <?php if ($successMessage): ?>
        <div class="success-message" id="successMessage"><?= $successMessage ?></div>
    <?php endif; ?>

    <h1>Thêm Sản Phẩm Mới</h1>
    
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="fabric_name">Tên Sản Phẩm:</label>
        <input type="text" id="fabric_name" name="fabric_name" required><br>

        <label for="fabric_color">Màu Sắc:</label>
        <input type="text" id="fabric_color" name="fabric_color"><br>

        <label for="fabric_price">Giá:</label>
        <input type="number" id="fabric_price" name="fabric_price" step="0.01" required><br>

        <label for="applied_date">Ngày Áp Dụng:</label>
        <input type="date" id="applied_date" name="applied_date" required><br>

        <label for="fabric_quantity">Số Lượng:</label>
        <input type="number" id="fabric_quantity" name="fabric_quantity" required><br>

        <label for="supplier_id">Nhà Cung Cấp:</label>
        <select id="supplier_id" name="supplier_id" required>
            <?php
            $stmt = $conn->query("SELECT SCode, Name FROM supplier");
            while ($supplier = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . $supplier['SCode'] . "'>" . $supplier['Name'] . "</option>";
            }
            ?>
        </select><br>

        <label for="product_image">Hình Ảnh Sản Phẩm:</label>
        <input type="file" id="product_image" name="product_image" accept="image/*" onchange="previewImage(event)"><br><br>

        <div class="image-preview">
            <img id="imagePreview" src="" alt="Hình ảnh sản phẩm sẽ xuất hiện ở đây">
        </div>

        <div class="rolls-container">
            <label for="roll_lengths">Chiều Dài Cuộn Vải (m):</label>
            <input type="number" step="0.01" name="roll_lengths[]" required>
            <button type="button" class="add-roll-btn" onclick="addRollInput()">Thêm cuộn vải</button>
        </div>

        <button type="submit">Thêm Sản Phẩm</button>
    </form>
</div>

<script>
    // Xem trước hình ảnh khi tải lên
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
