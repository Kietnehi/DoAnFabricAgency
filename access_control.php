<?php
// Include file kết nối cơ sở dữ liệu
include('nav.php');
include('connect.php');

// Câu lệnh cấp quyền
if (isset($_POST['grant_privileges'])) {
    $username = $_POST['username'];
    $database = $_POST['database'];
    $privileges = $_POST['privileges'];

    try {
        $grant_sql = "GRANT $privileges ON `$database`.* TO '$username'@'localhost'";
        $stmt = $conn->prepare($grant_sql);

        if ($stmt->execute()) {
            $message = "Cấp quyền thành công!";
        } else {
            $message = "Lỗi: Không thể cấp quyền.";
        }
    } catch (PDOException $e) {
        $message = "Lỗi: " . $e->getMessage();
    }
}

// Câu lệnh kiểm tra quyền
if (isset($_POST['check_privileges'])) {
    $check_username = $_POST['check_username'];

    try {
        $show_grants_sql = "SHOW GRANTS FOR '$check_username'@'localhost'";
        $stmt = $conn->prepare($show_grants_sql);
        $stmt->execute();

        $grants = "";
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $grants .= implode(", ", $row) . "<br>";
            }
        } else {
            $grants = "Không tìm thấy quyền của người dùng!";
        }
    } catch (PDOException $e) {
        $grants = "Lỗi: " . $e->getMessage();
    }
}

// Câu lệnh xóa người dùng
if (isset($_POST['delete_user'])) {
    $delete_username = $_POST['delete_username'];

    try {
        $delete_sql = "DROP USER '$delete_username'@'localhost'";
        $stmt = $conn->prepare($delete_sql);

        if ($stmt->execute()) {
            $message = "Xóa người dùng thành công!";
        } else {
            $message = "Lỗi: Không thể xóa người dùng.";
        }
    } catch (PDOException $e) {
        $message = "Lỗi: " . $e->getMessage();
    }
}

// Câu lệnh tạo người dùng
if (isset($_POST['create_user'])) {
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];

    try {
        $create_user_sql = "CREATE USER '$new_username'@'localhost' IDENTIFIED BY '$new_password'";
        $stmt = $conn->prepare($create_user_sql);

        if ($stmt->execute()) {
            $message = "Tạo người dùng thành công!";
        } else {
            $message = "Lỗi: Không thể tạo người dùng.";
        }
    } catch (PDOException $e) {
        $message = "Lỗi: " . $e->getMessage();
    }
}

/// Cập nhật thông tin kết nối
if (isset($_POST['update_credentials'])) {
    $new_user = $_POST['new_user'] ?? '';
    $new_password = $_POST['new_password'] ?? '';  // Nếu không có mật khẩu, gán rỗng

    $connect_file = 'connect.php';
    $file_contents = file_get_contents($connect_file);

    // Cập nhật tên người dùng nếu có giá trị
    if ($new_user) {
        $file_contents = preg_replace('/\$user = \'[^\']+\'/', "\$user = '$new_user'", $file_contents);
    }

    // Cập nhật mật khẩu nếu có giá trị
    if ($new_password === '') {
        // Nếu mật khẩu rỗng, gán một chuỗi rỗng vào connect.php
        $file_contents = preg_replace('/\$password = \'[^\']*\'/', "\$password = ''", $file_contents);
    } elseif ($new_password !== '') {
        // Nếu có mật khẩu mới, cập nhật mật khẩu
        $file_contents = preg_replace('/\$password = \'[^\']*\'/', "\$password = '$new_password'", $file_contents);
    }

    // Lưu lại thay đổi vào file
    file_put_contents($connect_file, $file_contents);

    $message = "Cập nhật thông tin kết nối thành công!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="role.css">
    <title>Quản lý Quyền Truy Cập Cơ Sở Dữ Liệu</title>
</head>
<body>
    <h2>Quản lý Quyền Truy Cập Cơ Sở Dữ Liệu</h2>

    <?php
    // Hiển thị thông báo
    if (isset($message)) {
        echo "<p>$message</p>";
    }
    if (isset($grants)) {
        echo "<p><strong>Quyền của người dùng:</strong><br>$grants</p>";
    }
    ?>

    <!-- Form tạo người dùng -->
    <form method="POST" action="">
        <h3>Tạo Người Dùng</h3>
        <label for="new_username">Tên người dùng:</label>
        <input type="text" id="new_username" name="new_username" required><br>

        <label for="new_password">Mật khẩu:</label>
        <input type="password" id="new_password" name="new_password" required><br>

        <button type="submit" name="create_user">Tạo Người Dùng</button>
    </form>

    <hr>

    <!-- Form cấp quyền -->
    <form method="POST" action="">
        <h3>Cấp Quyền</h3>
        <label for="username">Tên người dùng:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="database">Cơ sở dữ liệu:</label>
        <input type="text" id="database" name="database" required><br>

        <label for="privileges">Quyền:</label>
        <select name="privileges" id="privileges">
            <option value="SELECT">SELECT</option>
            <option value="INSERT">INSERT</option>
            <option value="UPDATE">UPDATE</option>
            <option value="DELETE">DELETE</option>
            <option value="ALL PRIVILEGES">ALL PRIVILEGES</option>
        </select><br>

        <button type="submit" name="grant_privileges">Cấp Quyền</button>
    </form>

    <hr>

    <!-- Form kiểm tra quyền -->
    <form method="POST" action="">
        <h3>Kiểm Tra Quyền</h3>
        <label for="check_username">Tên người dùng:</label>
        <input type="text" id="check_username" name="check_username" required><br>

        <button type="submit" name="check_privileges">Kiểm Tra Quyền</button>
    </form>

    <hr>

    <!-- Form xóa người dùng -->
    <form method="POST" action="">
        <h3>Xóa Người Dùng</h3>
        <label for="delete_username">Tên người dùng:</label>
        <input type="text" id="delete_username" name="delete_username" required><br>

        <button type="submit" name="delete_user">Xóa Người Dùng</button>
    </form>

    <hr>

  <!-- Form cập nhật thông tin kết nối -->
<form method="POST" action="">
    <h3>Cập Nhật Thông Tin Kết Nối</h3>
    <label for="new_user">Tên người dùng (username):</label>
    <input type="text" id="new_user" name="new_user"><br>

    <label for="new_password">Mật khẩu:</label>
    <input type="password" id="new_password" name="new_password"><br>

    <button type="submit" name="update_credentials">Cập Nhật Thông Tin</button>
</form>

</body>
</html>
