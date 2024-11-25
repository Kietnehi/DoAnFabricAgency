<?php
// Start session if it hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Handle search query
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

// Pagination settings
$limit = 10; // Number of entries per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch total number of suppliers
$totalSql = "SELECT COUNT(*) FROM supplier WHERE Name LIKE :searchTerm OR Phone LIKE :searchTerm";
$totalStmt = $conn->prepare($totalSql);
$searchTerm = "%" . $searchQuery . "%";
$totalStmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
$totalStmt->execute();
$totalRows = $totalStmt->fetchColumn();
$totalPages = ceil($totalRows / $limit);

// Fetch suppliers for the current page
$sql = "SELECT * FROM supplier WHERE Name LIKE :searchTerm OR Phone LIKE :searchTerm LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhà cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="supplier.css">
    <style>
        /* Style cho nút xóa */
        .btn-delete {
            background: #f44336;
            /* Màu đỏ */
            color: white;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
            margin: 5px;
        }

        .btn-delete:hover {
            background: #d32f2f;
            /* Màu đỏ đậm hơn khi hover */
            transform: scale(1.05);
            /* Nút lớn hơn một chút khi hover */
            margin: 5px;
            color: white;
        }

        /* Style cho nút sửa */
        .btn-edit {
            background: #4CAF50;
            /* Màu xanh lá cây */
            margin: 5px;
            color: white;
            border: none;
            padding: 4px 8px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-edit:hover {
            background: #45a049;
            /* Màu xanh lá cây đậm hơn khi hover */
            transform: scale(1.05);
            /* Nút lớn hơn một chút khi hover */
            margin: 5px;
            color: white;
        }

        .btn-view-details {
            background-color: #17a2b8;
            margin: 5px;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-view-details:hover {
            background-color: #138496;
            color: white;
            margin: 5px;
        }

        .btn,a i {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Quản lý Nhà cung cấp</h1>
        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo tên hoặc số điện thoại" value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
            </div>
        </form>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã nhà cung cấp</th>
                    <th>Tên</th>
                    <th>Địa chỉ</th>
                    <th>Số tài khoản ngân hàng</th>
                    <th>Mã số thuế</th>
                    <th>Số điện thoại</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['SCode']); ?></td>
                        <td><?php echo htmlspecialchars($row['Name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Address']); ?></td>
                        <td><?php echo htmlspecialchars($row['BankAccount']); ?></td>
                        <td><?php echo htmlspecialchars($row['TaxCode']); ?></td>
                        <td><?php echo htmlspecialchars($row['Phone']); ?></td>
                        <td>
                            <a href="edit_supplier.php?id=<?php echo $row['SCode']; ?>" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i> sửa</a>
                            <a href="delete_supplier.php?id=<?php echo $row['SCode']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?');"><i class="fas fa-trash"></i> Xóa</a>
                            <button class="btn btn-sm btn-view-details" data-bs-toggle="modal" data-bs-target="#viewProductsModal" data-id="<?php echo $row['SCode']; ?>"><i class="fas fa-eye"></i> Xem chi tiết</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Pagination links -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="viewProductsModal" tabindex="-1" aria-labelledby="viewProductsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProductsModalLabel">Chi tiết sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="productsContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var viewProductsModal = document.getElementById('viewProductsModal');
            viewProductsModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var supplierId = button.getAttribute('data-id');

                var modalTitle = viewProductsModal.querySelector('.modal-title');
                var modalBody = viewProductsModal.querySelector('.modal-body #productsContent');

                var supplierName = button.closest('tr').querySelector('td:nth-child(2)').textContent;
                modalTitle.textContent = 'Chi tiết sản phẩm của nhà cung cấp ' + supplierName;

                fetch('fetch_products.php?id=' + supplierId)
                    .then(response => response.text())
                    .then(data => {
                        modalBody.innerHTML = data;
                    });
            });
        });
    </script>
</body>

</html>

<?php
$conn = null;
?>