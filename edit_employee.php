<?php
// Kết nối đến cơ sở dữ liệu
include('connect.php');
include "nav.php";  // Thêm phần điều hướng (nếu có)

// Lấy ID nhân viên từ URL
if (isset($_GET['ECode'])) {
    $ECode = $_GET['ECode'];

    // Lấy thông tin nhân viên từ cơ sở dữ liệu
    $sql = "SELECT * FROM employee WHERE ECode = :ECode";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ECode', $ECode, PDO::PARAM_INT);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<p style='color: red;'>Không tìm thấy nhân viên với mã này.</p>";
        exit();
    }
}

// Xử lý cập nhật thông tin nhân viên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $Gender = $_POST['Gender'];
    $Address = $_POST['Address'];
    $Phone = $_POST['Phone'];
    $Role = $_POST['Role'];

    // Cập nhật thông tin nhân viên vào cơ sở dữ liệu
    $update_sql = "UPDATE employee SET Fname = :Fname, Lname = :Lname, Gender = :Gender, Address = :Address, Phone = :Phone, Role = :Role WHERE ECode = :ECode";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':Fname', $Fname, PDO::PARAM_STR);
    $stmt->bindParam(':Lname', $Lname, PDO::PARAM_STR);
    $stmt->bindParam(':Gender', $Gender, PDO::PARAM_STR);
    $stmt->bindParam(':Address', $Address, PDO::PARAM_STR);
    $stmt->bindParam(':Phone', $Phone, PDO::PARAM_STR);
    $stmt->bindParam(':Role', $Role, PDO::PARAM_STR);
    $stmt->bindParam(':ECode', $ECode, PDO::PARAM_INT);

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
        <label for="Fname">Tên:</label><br>
        <input type="text" id="Fname" name="Fname" value="<?php echo htmlspecialchars($employee['Fname']); ?>" required><br><br>

        <!-- Họ -->
        <label for="Lname">Họ:</label><br>
        <input type="text" id="Lname" name="Lname" value="<?php echo htmlspecialchars($employee['Lname']); ?>" required><br><br>

        <!-- Giới tính -->
        <label for="Gender">Giới tính:</label><br>
        <select id="Gender" name="Gender" required>
            <option value="Male" <?php if ($employee['Gender'] == 'Male') echo 'selected'; ?>>Nam</option>
            <option value="Female" <?php if ($employee['Gender'] == 'Female') echo 'selected'; ?>>Nữ</option>
        </select><br><br>

        <!-- Địa chỉ -->
        <label for="Address">Địa chỉ:</label><br>
        <input type="text" id="Address" name="Address" value="<?php echo htmlspecialchars($employee['Address']); ?>"><br><br>

        <!-- Số điện thoại -->
        <label for="Phone">Số điện thoại:</label><br>
        <input type="text" id="Phone" name="Phone" value="<?php echo htmlspecialchars($employee['Phone']); ?>"><br><br>

        <!-- Vai trò -->
        <label for="Role">Vai trò:</label><br>
        <select id="Role" name="Role" required>
            <option value="Manager" <?php if ($employee['Role'] == 'Manager') echo 'selected'; ?>>Quản lý</option>
            <option value="PartnerStaff" <?php if ($employee['Role'] == 'PartnerStaff') echo 'selected'; ?>>Nhân viên đối tác</option>
            <option value="OperationalStaff" <?php if ($employee['Role'] == 'OperationalStaff') echo 'selected'; ?>>Nhân viên vận hành</option>
            <option value="OfficeStaff" <?php if ($employee['Role'] == 'OfficeStaff') echo 'selected'; ?>>Nhân viên văn phòng</option>
        </select><br><br>

        <!-- Nút cập nhật -->
        <button type="submit">Cập nhật</button>
    </form>

    <br>
    <a href="employees.php">Quay lại danh sách nhân viên</a>
</body>
</html>
