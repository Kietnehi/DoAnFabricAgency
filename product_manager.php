<?php
include 'connect.php'; // Kết nối với cơ sở dữ liệu

// Số sản phẩm trên mỗi trang
$productsPerPage = 10;

// Xác định trang hiện tại
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Tính toán chỉ mục bắt đầu
$offset = ($page - 1) * $productsPerPage;

// Lấy tổng số sản phẩm
$totalProductsStmt = $conn->prepare("SELECT COUNT(*) FROM category");
$totalProductsStmt->execute();
$totalProducts = $totalProductsStmt->fetchColumn();

// Tính tổng số trang
$totalPages = ceil($totalProducts / $productsPerPage);

// Lấy danh sách sản phẩm cho trang hiện tại
$sql = "SELECT c.CCode, c.Name, c.Color, c.Price, c.AppliedDate, c.RemainQuantity, c.img, s.Name AS SupplierName 
        FROM category c 
        LEFT JOIN supplier s ON c.SCode = s.SCode
        LIMIT :offset, :productsPerPage";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hàm xóa sản phẩm
if (isset($_GET['delete'])) {
    $CCode = $_GET['delete'];

    try {
        // Bắt đầu giao dịch
        $conn->beginTransaction();

        // Xóa các bản ghi trong `supplyhistory` liên quan đến `category` có `CCode` tương ứng
        $sql = "DELETE FROM supplyhistory WHERE CCode = :CCode";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':CCode' => $CCode]);

        // Xóa sản phẩm trong bảng `category`
        $sql = "DELETE FROM category WHERE CCode = :CCode";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':CCode' => $CCode]);

        // Xác nhận giao dịch
        $conn->commit();

        // Chuyển hướng sau khi xóa thành công
        header('Location: product_manager.php?page=' . $page);
        exit;
    } catch (PDOException $e) {
        // Hủy giao dịch nếu có lỗi
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

include 'nav.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="product_manager.css">
    <title>Quản lý sản phẩm</title>
    <style>
        /* Định dạng bảng */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        /* Định dạng hình ảnh */
        img {
            border-radius: 5px;
        }

        /* Định dạng nút */
        .btn {
            padding: 5px 10px;
            margin: 2px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 14px;
            text-align: center;
            cursor: pointer;
            display: inline-block;
        }
        .btn-edit {
            background-color: #007bff;
        }
        .btn-edit:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        /* Phân trang */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            list-style-type: none;
            padding: 0;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination a {
            padding: 8px 12px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #0056b3;
        }
        .pagination .active {
            font-weight: bold;
            background-color: #0056b3;
        }
    </style>
    <script>
        function confirmDelete(url) {
            if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này không?")) {
                window.location.href = url;
            }
        }
    </script>
</head>
<body>

<h2>Danh sách sản phẩm</h2>
<table>
    <thead>
        <tr>
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Màu sắc</th>
            <th>Giá hiện tại</th>
            <th>Ngày áp dụng</th>
            <th>Số lượng còn lại</th>
            <th>Nhà cung cấp</th>
            <th>Hình ảnh</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= htmlspecialchars($product['CCode']); ?></td>
            <td><?= htmlspecialchars($product['Name']); ?></td>
            <td><?= htmlspecialchars($product['Color']); ?></td>
            <td><?= number_format($product['Price'], 2); ?> USD</td>
            <td><?= htmlspecialchars($product['AppliedDate']); ?></td>
            <td><?= htmlspecialchars($product['RemainQuantity']); ?></td>
            <td><?= htmlspecialchars($product['SupplierName']); ?></td>
            <td>
                <?php if ($product['img']): ?>
                    <img src="img/<?= htmlspecialchars($product['img']); ?>" alt="Hình ảnh sản phẩm" width="50">
                <?php else: ?>
                    <p>Chưa có hình ảnh</p>
                <?php endif; ?>
            </td>
            <td>
                <div class="action-buttons">
                    <a href="edit_product.php?edit=<?= $product['CCode']; ?>" class="btn btn-edit">Sửa</a>
                    <a href="javascript:void(0);" onclick="confirmDelete('product_manager.php?delete=<?= $product['CCode']; ?>&page=<?= $page ?>')" class="btn btn-danger">Xóa</a>
                    <a href="add_fabric_types.php?CCode=<?= $product['CCode']; ?>" class="btn btn-success">Thêm</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Phân trang -->
<ul class="pagination">
    <?php if ($page > 1): ?>
        <li><a href="?page=<?= $page - 1; ?>">&laquo; Trước</a></li>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li>
            <a href="?page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>">
                <?= $i; ?>
            </a>
        </li>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <li><a href="?page=<?= $page + 1; ?>">Sau &raquo;</a></li>
    <?php endif; ?>
</ul>

</body>
</html>
