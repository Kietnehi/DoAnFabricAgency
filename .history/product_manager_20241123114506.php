<?php
include 'connect.php'; // Kết nối với cơ sở dữ liệu

// Số sản phẩm trên mỗi trang
$productsPerPage = 10;

// Xác định trang hiện tại
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// Tính toán chỉ mục bắt đầu
$offset = ($page - 1) * $productsPerPage;

// Lấy từ khóa tìm kiếm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Lấy tổng số sản phẩm (có hoặc không có tìm kiếm)
$totalProductsStmt = $conn->prepare("SELECT COUNT(*) FROM category c 
                                     LEFT JOIN supplier s ON c.SCode = s.SCode 
                                     WHERE c.Name LIKE :search OR c.CCode LIKE :search OR s.Name LIKE :search");
$totalProductsStmt->execute([':search' => "%$search%"]);
$totalProducts = $totalProductsStmt->fetchColumn();

// Tính tổng số trang
$totalPages = ceil($totalProducts / $productsPerPage);

// Lấy danh sách sản phẩm và thông tin chi tiết từ bảng supplier
$sql = "SELECT 
            c.CCode, c.Name AS ProductName, c.Color, c.Price, c.AppliedDate, c.RemainQuantity, c.img, 
            s.SCode, s.Name AS SupplierName, s.Address, s.Phone, s.BankAccount, s.TaxCode 
        FROM category c 
        LEFT JOIN supplier s ON c.SCode = s.SCode
        WHERE c.Name LIKE :search OR c.CCode LIKE :search OR s.Name LIKE :search
        LIMIT :offset, :productsPerPage";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':productsPerPage', $productsPerPage, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Hàm xóa sản phẩm
if (isset($_GET['delete'])) {
    $CCode = $_GET['delete'];

    try {
        $conn->beginTransaction();

        $sql = "DELETE FROM supplyhistory WHERE CCode = :CCode";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':CCode' => $CCode]);

        $sql = "DELETE FROM category WHERE CCode = :CCode";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':CCode' => $CCode]);

        $conn->commit();

        header('Location: product_manager.php?page=' . $page);
        exit;
    } catch (PDOException $e) {
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
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        img {
            border-radius: 5px;
        }

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

        .btn-warning {
            background-color: #ffc107;
            color: white;
        }

        .btn-warning:hover {
            background: linear-gradient(to right, #ffc107, #ff6f00);
            /* Vàng chuyển cam */
            color: #fff;
            /* Chữ trắng */
        }

        .pagination {
            display: flex;
            justify-content: center;
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

        .search-container {
            margin: 30px;
            text-align: center;
        }

        .search-container input[type="text"] {
            width: 300px;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-container button {
            padding: 8px 16px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #0056b3;
        }

        .btn-add {
    margin: 20px 100px;
    padding: 10px 20px; /* Kích thước lớn hơn */
    background: linear-gradient(to right, #ff7f50, #ff6bd6); /* Gradient hồng cam */
    color: white; /* Chữ trắng nổi bật */
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s; /* Hiệu ứng mượt mà */
    float: right; /* Đặt nút bên phải */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Tạo bóng */
}

.btn-add:hover {
    background: linear-gradient(to right, #e55e26, #d94bad); /* Gradient đậm hơn khi hover */
    transform: scale(1.05); /* Tăng nhẹ kích thước khi hover */
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15); /* Tăng bóng để nổi bật */
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
    <div class="search-container">
        <form method="GET" action="product_manager.php">
            <input type="text" name="search" value="<?= htmlspecialchars($search); ?>" placeholder="Nhập mã, tên sản phẩm hoặc nhà cung cấp...">
            <button type="submit">Tìm kiếm</button>
        </form>
    </div>
    <a href="add_fabric_types.php?CCode=" class="btn-add"><i class="fas fa-plus"></i>Thêm sản phẩm</a>
    <table>
        <thead>
            <tr>
                <th>Mã sản phẩm</th>
                <th>Tên sản phẩm</th>
                <th>Màu sắc</th>
                <th>Giá</th>
                <th>Ngày áp dụng</th>
                <th>Số lượng</th>
                <th>Nhà cung cấp</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Tài khoản ngân hàng</th>
                <th>Mã số thuế</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['CCode']); ?></td>
                        <td><?= htmlspecialchars($product['SupplierName']); ?></td>
                        <td><?= htmlspecialchars($product['ProductName']); ?></td>
                        <td><?= htmlspecialchars($product['Color']); ?></td>
                        <td><?= number_format($product['Price'], 2); ?> USD</td>
                        <td><?= htmlspecialchars($product['AppliedDate']); ?></td>
                        <td><?= htmlspecialchars($product['RemainQuantity']); ?></td>
                        <td><?= htmlspecialchars($product['Address']); ?></td>
                        <td><?= htmlspecialchars($product['Phone']); ?></td>
                        <td><?= htmlspecialchars($product['BankAccount']); ?></td>
                        <td><?= htmlspecialchars($product['TaxCode']); ?></td>
                        <td>
                            <?php if ($product['img']): ?>
                                <img src="img/<?= htmlspecialchars($product['img']); ?>" alt="Hình ảnh sản phẩm" width="50">
                            <?php else: ?>
                                <p>Chưa có hình ảnh</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_product.php?edit=<?= $product['CCode']; ?>" class="btn btn-edit">Sửa</a>
                            <a href="javascript:void(0);" onclick="confirmDelete('product_manager.php?delete=<?= $product['CCode']; ?>&page=<?= $page ?>')" class="btn btn-danger">Xóa</a>
                            <?php if ($product['SCode']): ?>
                                <a href="supplier_details.php?SCode=<?= $product['SCode']; ?>" class="btn btn-warning">Chi tiết</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13">Không tìm thấy sản phẩm nào phù hợp.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li><a href="?search=<?= urlencode($search); ?>&page=<?= $page - 1; ?>">&laquo; Trước</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li>
                <a href="?search=<?= urlencode($search); ?>&page=<?= $i; ?>" class="<?= $i == $page ? 'active' : ''; ?>">
                    <?= $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <li><a href="?search=<?= urlencode($search); ?>&page=<?= $page + 1; ?>">Sau &raquo;</a></li>
        <?php endif; ?>
    </ul>

</body>

</html>