<?php
// Kết nối đến cơ sở dữ liệu
include('connect.php');
include "nav.php";  // Thêm phần điều hướng (nếu có)

// Lấy ID nhân viên từ URL
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];

    // Lấy thông tin nhân viên từ cơ sở dữ liệu
    $sql = "SELECT * FROM employees WHERE employee_id = :employee_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Không tìm thấy nhân viên với ID này.";
        exit();
    }
}

// Xử lý cập nhật thông tin nhân viên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    // Cập nhật thông tin nhân viên vào cơ sở dữ liệu
    $update_sql = "UPDATE employees SET first_name = :first_name, last_name = :last_name, gender = :gender, address = :address, phone = :phone, role = :role WHERE employee_id = :employee_id";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':gender', $gender, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);

    // Kiểm tra xem có cập nhật thành công không
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Cập nhật thông tin nhân viên thành công!</p>";
    } else {
        echo "<p style='color: red;'>Lỗi khi cập nhật thông tin.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin nhân viên</title>
</head>
<body>
    <h2>Chỉnh sửa thông tin nhân viên</h2>

    <!-- Form chỉnh sửa thông tin nhân viên -->
    <form method="post">
        <!-- Tên -->
        <label for="first_name">Tên:</label><br>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required><br><br>

        <!-- Họ -->
        <label for="last_name">Họ:</label><br>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required><br><br>

        <!-- Giới tính -->
        <label for="gender">Giới tính:</label><br>
        <select id="gender" name="gender" required>
            <option value="Nam" <?php if ($employee['gender'] == 'Nam') echo 'selected'; ?>>Nam</option>
            <option value="Nữ" <?php if ($employee['gender'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
        </select><br><br>

        <!-- Địa chỉ -->
        <label for="address">Địa chỉ:</label><br>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($employee['address']); ?>"><br><br>

        <!-- Số điện thoại -->
        <label for="phone">Số điện thoại:</label><br>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['phone']); ?>"><br><br>

        <!-- Vai trò -->
        <label for="role">Vai trò:</label><br>
        <select id="role" name="role" required>
            <option value="Manager" <?php if ($employee['role'] == 'Manager') echo 'selected'; ?>>Quản lý</option>
            <option value="Partner" <?php if ($employee['role'] == 'Partner') echo 'selected'; ?>>Đối tác</option>
            <option value="Operations" <?php if ($employee['role'] == 'Operations') echo 'selected'; ?>>Hoạt động</option>
            <option value="Office" <?php if ($employee['role'] == 'Office') echo 'selected'; ?>>Văn phòng</option>
        </select><br><br>

        <!-- Nút cập nhật -->
        <button type="submit">Cập nhật</button>
    </form>

    <br>
    <a href="employees.php">Quay lại danh sách nhân viên</a>
</body>
</html>
