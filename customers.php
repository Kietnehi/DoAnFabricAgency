<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php'; // Kết nối cơ sở dữ liệu
include 'nav.php'; // Bao gồm thanh điều hướng

// Xử lý tìm kiếm và sắp xếp
$query = isset($_GET['query']) ? $_GET['query'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'CusId';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

// Thiết lập phân trang
$limit = 10; // Số bản ghi mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Ghi lại thời gian bắt đầu
$start_time = microtime(true);

// Chuẩn bị câu truy vấn SQL cho tìm kiếm, sắp xếp, và liên kết bảng
$sql = "SELECT c.CusId, c.Fname, c.Lname, c.Phone, c.Address, c.Dept, c.ECode,
        CONCAT('NV ', e.ECode, ' - ', e.Fname, ' ', e.Lname) AS EmployeeInfo
        FROM customer c
        LEFT JOIN employee e ON c.ECode = e.ECode   
        WHERE (c.Phone LIKE :query)
        OR (CONCAT(c.Fname, ' ', c.Lname) LIKE :query 
            OR c.Fname LIKE :query 
            OR c.Lname LIKE :query
            OR c.Address LIKE :query)
        AND e.Role = 'OfficeStaff'
        ORDER BY $order_by $order_dir
        LIMIT :limit OFFSET :offset";



$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ghi lại thời gian kết thúc và tính thời gian tìm kiếm
$end_time = microtime(true);
$search_time = round($end_time - $start_time, 4); // Thời gian tìm kiếm (giây)

// Đếm tổng số bản ghi để tính tổng số trang
$total_stmt = $conn->prepare("SELECT COUNT(*) FROM customer WHERE Fname LIKE :query OR Lname LIKE :query OR Phone LIKE :query");
$total_stmt->execute([':query' => "%$query%"]);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Đảo chiều sắp xếp để sử dụng cho các lần nhấp tiếp theo
$new_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Khách hàng</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="customers.css">
    <style>
        /* Nút sửa (màu xanh lá) */
        .btn-edit {
            background: linear-gradient(to right, #28a745, #218838);
            /* Gradient xanh lá */
            color: white;
            /* Màu chữ */
            border: none;
            /* Không viền */
            padding: 8px 15px;
            /* Khoảng cách bên trong */
            font-size: 14px;
            /* Kích thước chữ */
            font-weight: bold;
            /* Chữ đậm */
            text-decoration: none;
            /* Bỏ gạch chân */
            border-radius: 5px;
            /* Bo tròn góc */
            cursor: pointer;
            /* Hiệu ứng con trỏ */
            transition: all 0.3s ease;
            /* Hiệu ứng mượt mà */
            margin-right: 5px;
            /* Khoảng cách giữa các nút */
        }

        /* Hover cho nút sửa */
        .btn-edit:hover {
            background: linear-gradient(to right, #218838, #19692c);
            /* Gradient đậm hơn */
            transform: scale(1.05);
            /* Phóng to nhẹ */
        }

        /* Nút xóa (màu đỏ) */
        .btn-delete {
            background: linear-gradient(to right, #dc3545, #c82333);
            /* Gradient đỏ */
            color: white;
            border: none;
            padding: 8px 15px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        /* Hover cho nút xóa */
        .btn-delete:hover {
            background: linear-gradient(to right, #c82333, #a71d2a);
            /* Gradient đậm hơn */
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Danh Sách Khách Hàng</h1>

        <!-- Form tìm kiếm khách hàng -->
        <form method="GET" action="customers.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm theo tên, số điện thoại, hoặc địa chỉ..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <!-- Hiển thị thời gian tìm kiếm -->
        <div class="search-time">
            <p><strong>Thời gian tìm kiếm:</strong> <?= $search_time ?> giây</p>
        </div>

        <!-- Bảng hiển thị danh sách khách hàng -->
        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=CusId&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Fname&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Tên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Phone&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Số điện thoại</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=Dept&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Công nợ</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=EmployeeInfo&order_dir=<?= $new_order_dir ?>"><i class="fas fa-sort"></i> Nhân viên phụ trách</a></th>
                <th>Địa chỉ</th> <!-- Thêm cột Địa chỉ -->
                <th>Hành động</th>
            </tr>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= htmlspecialchars($customer['CusId']); ?></td>
                        <td><?= htmlspecialchars($customer['Fname'] . " " . $customer['Lname']); ?></td>
                        <td><?= htmlspecialchars($customer['Phone']); ?></td>
                        <td><?= htmlspecialchars(number_format($customer['Dept'], 2)); ?> USD</td>
                        <td><?= htmlspecialchars($customer['EmployeeInfo'] ?? 'Không xác định'); ?></td>
                        <td><?= htmlspecialchars($customer['Address']); ?></td> <!-- Hiển thị địa chỉ -->
                        <td>
                            <a href="edit_customer.php?id=<?= $customer['CusId']; ?>" class="btn-edit">Sửa</a>
                            <?php if ($customer['Dept'] < 1): ?>
                                <a href="delete_customer.php?id=<?= $customer['CusId']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa khách hàng này?');">Xóa</a>
                            <?php else: ?>
                                <span class="btn-delete disabled" title="Không thể xóa khách hàng có công nợ lớn hơn 1">Xóa</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Không tìm thấy khách hàng nào.</td>
                </tr>
            <?php endif; ?>
        </table>

       <!-- Phân trang -->
<div class="pagination">
    <!-- Nút First và Previous -->
    <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=1" class="<?= ($page == 1) ? 'disabled' : '' ?>">&laquo; Đầu</a>
    <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=<?= max(1, $page - 1) ?>" class="<?= ($page == 1) ? 'disabled' : '' ?>">Trước</a>

    <?php
    // Giới hạn số trang hiển thị (Ví dụ: hiển thị tối đa 5 trang)
    $max_pages_to_show = 5;
    $half_max_pages = floor($max_pages_to_show / 2);

    // Tính toán phạm vi trang hiển thị (start_page và end_page)
    $start_page = max(1, $page - $half_max_pages);
    $end_page = min($total_pages, $page + $half_max_pages);

    // Điều chỉnh lại phạm vi hiển thị nếu trang đầu hoặc trang cuối bị cắt
    if ($page - $half_max_pages < 1) {
        $end_page = min($total_pages, $end_page + (1 - ($page - $half_max_pages)));
    } elseif ($page + $half_max_pages > $total_pages) {
        $start_page = max(1, $start_page - ($page + $half_max_pages - $total_pages));
    }

    // Hiển thị các trang trong phạm vi
    for ($i = $start_page; $i <= $end_page; $i++):
    ?>
        <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <!-- Nút Next và Last -->
    <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=<?= min($total_pages, $page + 1) ?>" class="<?= ($page == $total_pages) ? 'disabled' : '' ?>">Tiếp theo</a>
    <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=<?= $total_pages ?>" class="<?= ($page == $total_pages) ? 'disabled' : '' ?>">Cuối &raquo;</a>
</div>

    </div>
</body>

</html>