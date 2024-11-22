<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Thiết lập phân trang
$limit = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Xử lý tìm kiếm và sắp xếp
$query = isset($_GET['query']) ? $_GET['query'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'ECode';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

// Truy vấn SQL tìm kiếm và sắp xếp nhân viên
$sql = "SELECT * FROM employee 
        WHERE Fname LIKE :query OR Lname LIKE :query OR Phone LIKE :query 
        ORDER BY $order_by $order_dir 
        LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính tổng số bản ghi để tính tổng số trang
$total_stmt = $conn->prepare("SELECT COUNT(*) FROM employee WHERE Fname LIKE :query OR Lname LIKE :query OR Phone LIKE :query");
$total_stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$total_stmt->execute();
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Đảo chiều sắp xếp
$new_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhân viên</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="employee.css">
</head>

<body>
    <div class="container">
        <h1>Danh Sách Nhân Viên</h1>

        <!-- Form tìm kiếm nhân viên -->
        <form method="GET" action="employees.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm theo tên hoặc số điện thoại..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit">Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <!-- Bảng hiển thị danh sách nhân viên -->
        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=ECode&order_dir=<?= $new_order_dir ?>" class="sortable">ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Fname&order_dir=<?= $new_order_dir ?>" class="sortable">Tên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Gender&order_dir=<?= $new_order_dir ?>" class="sortable">Giới tính</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Role&order_dir=<?= $new_order_dir ?>" class="sortable">Chức vụ</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Phone&order_dir=<?= $new_order_dir ?>" class="sortable">Số điện thoại</a></th>
                <th>Hành động</th>
            </tr>
            <?php if (!empty($employees)): ?>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= htmlspecialchars($employee['ECode']); ?></td>
                        <td><?= htmlspecialchars($employee['Fname'] . " " . $employee['Lname']); ?></td>
                        <td><?= htmlspecialchars($employee['Gender']); ?></td>
                        <td><?= htmlspecialchars($employee['Role']); ?></td>
                        <td><?= htmlspecialchars($employee['Phone']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_employee.php?ECode=<?= $employee['ECode']; ?>" class="edit-btn">Sửa</a>
                            <a href="delete_employee.php?ECode=<?= $employee['ECode']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Không tìm thấy nhân viên nào.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Phân trang -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>