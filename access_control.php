<?php
include('nav.php');
include('connect.php');

$message = '';
$grants = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['grant_privileges'])) {
        $username = $_POST['username'];
        $database = $_POST['database'];
        $privileges = $_POST['privileges'];

        try {
            $grant_sql = "GRANT $privileges ON `$database`.* TO :username@'localhost'";
            $stmt = $conn->prepare($grant_sql);
            $stmt->bindParam(':username', $username);

            if ($stmt->execute()) {
                $message = "Cấp quyền thành công!";
            } else {
                $message = "Lỗi: Không thể cấp quyền.";
            }
        } catch (PDOException $e) {
            $message = "Lỗi: " . $e->getMessage();
        }
    }

    if (isset($_POST['check_privileges'])) {
        $check_username = $_POST['check_username'];

        try {
            $show_grants_sql = "SHOW GRANTS FOR :username@'localhost'";
            $stmt = $conn->prepare($show_grants_sql);
            $stmt->bindParam(':username', $check_username);
            $stmt->execute();

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

    if (isset($_POST['delete_user'])) {
        $delete_username = $_POST['delete_username'];

        try {
            $delete_sql = "DROP USER :username@'localhost'";
            $stmt = $conn->prepare($delete_sql);
            $stmt->bindParam(':username', $delete_username);

            if ($stmt->execute()) {
                $message = "Xóa người dùng thành công!";
            } else {
                $message = "Lỗi: Không thể xóa người dùng.";
            }
        } catch (PDOException $e) {
            $message = "Lỗi: " . $e->getMessage();
        }
    }

    if (isset($_POST['create_user'])) {
        $new_username = $_POST['new_username'];
        $new_password = $_POST['new_password'];

        try {
            // Kiểm tra người dùng tồn tại
            $check_user_sql = "SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = :username AND host = 'localhost')";
            $stmt = $conn->prepare($check_user_sql);
            $stmt->bindParam(':username', $new_username);
            $stmt->execute();
            $user_exists = $stmt->fetchColumn();

            if ($user_exists) {
                // Xóa người dùng nếu tồn tại
                $delete_user_sql = "DROP USER :username@'localhost'";
                $stmt = $conn->prepare($delete_user_sql);
                $stmt->bindParam(':username', $new_username);
                $stmt->execute();
            }

            // Tạo người dùng mới
            $create_user_sql = "CREATE USER :username@'localhost' IDENTIFIED BY :password";
            $stmt = $conn->prepare($create_user_sql);
            $stmt->bindParam(':username', $new_username);
            $stmt->bindParam(':password', $new_password);

            if ($stmt->execute()) {
                $message = "Tạo người dùng thành công!";
            } else {
                $message = "Lỗi: Không thể tạo người dùng.";
            }
        } catch (PDOException $e) {
            $message = "Lỗi: " . $e->getMessage();
        }
    }

    if (isset($_POST['update_credentials'])) {
        $new_user = $_POST['new_user'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        $connect_file = 'connect.php';
        $file_contents = file_get_contents($connect_file);

        if ($new_user) {
            $file_contents = preg_replace('/\$user = \'[^\']+\'/', "\$user = '$new_user'", $file_contents);
        }

        if ($new_password === '') {
            $file_contents = preg_replace('/\$password = \'[^\']*\'/', "\$password = ''", $file_contents);
        } elseif ($new_password !== '') {
            $file_contents = preg_replace('/\$password = \'[^\']*\'/', "\$password = '$new_password'", $file_contents);
        }

        file_put_contents($connect_file, $file_contents);

        $message = "Cập nhật thông tin kết nối thành công!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Quản lý Quyền Truy Cập Cơ Sở Dữ Liệu</title>
</head>
<body>
    <div class="container mt-5">
        <h1 style="text-align: center; font-size: 60px; background-color: #f0f8ff; color: #4b0082;">Quản lý Quyền Truy Cập Cơ Sở Dữ Liệu</h1>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($grants): ?>
            <div class="alert alert-info"><strong>Quyền của người dùng:</strong><br><?php echo $grants; ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="mb-4">
            <h3>Tạo Người Dùng</h3>
            <div class="form-group">
                <label for="new_username">Tên người dùng:</label>
                <input type="text" id="new_username" name="new_username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu:</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <button type="submit" name="create_user" class="btn btn-primary">Tạo Người Dùng</button>
        </form>

        <form method="POST" action="" class="mb-4">
            <h3>Cấp Quyền</h3>
            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="database">Cơ sở dữ liệu:</label>
                <input type="text" id="database" name="database" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="privileges">Quyền:</label>
                <select name="privileges" id="privileges" class="form-control">
                    <option value="SELECT">SELECT</option>
                    <option value="INSERT">INSERT</option>
                    <option value="UPDATE">UPDATE</option>
                    <option value="DELETE">DELETE</option>
                    <option value="ALL PRIVILEGES">ALL PRIVILEGES</option>
                </select>
            </div>
            <button type="submit" name="grant_privileges" class="btn btn-primary">Cấp Quyền</button>
        </form>

        <form method="POST" action="" class="mb-4">
            <h3>Kiểm Tra Quyền</h3>
            <div class="form-group">
                <label for="check_username">Tên người dùng:</label>
                <input type="text" id="check_username" name="check_username" class="form-control" required>
            </div>
            <button type="submit" name="check_privileges" class="btn btn-primary">Kiểm Tra Quyền</button>
        </form>

        <form method="POST" action="" class="mb-4">
            <h3>Xóa Người Dùng</h3>
            <div class="form-group">
                <label for="delete_username">Tên người dùng:</label>
                <input type="text" id="delete_username" name="delete_username" class="form-control" required>
            </div>
            <button type="submit" name="delete_user" class="btn btn-danger">Xóa Người Dùng</button>
        </form>

        <form method="POST" action="">
            <h3>Cập Nhật Thông Tin Kết Nối</h3>
            <div class="form-group">
                <label for="new_user">Tên người dùng (username):</label>
                <input type="text" id="new_user" name="new_user" class="form-control">
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu:</label>
                <input type="password" id="new_password" name="new_password" class="form-control">
            </div>
            <button type="submit" name="update_credentials" class="btn btn-primary">Cập Nhật Thông Tin</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>