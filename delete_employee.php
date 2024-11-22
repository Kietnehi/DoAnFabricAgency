<?php
ob_start();
include('connect.php');
include 'nav.php'; // Bao gồm thanh điều hướng nếu cần thiết

// Kiểm tra xem có ID nhân viên được truyền vào không
if (isset($_GET['ECode'])) {
    $employee_id = $_GET['ECode'];

    try {
        // Bắt đầu giao dịch để đảm bảo tính toàn vẹn của dữ liệu
        $conn->beginTransaction();

        // Xóa các bản ghi liên quan trong các bảng phụ thuộc trước khi xóa nhân viên

        // Xóa các đơn hàng liên quan đến nhân viên
        $deleteOrders = "DELETE FROM orders WHERE ECode = :employee_id";
        $stmt = $conn->prepare($deleteOrders);
        $stmt->execute([':employee_id' => $employee_id]);

        // Xóa các nhà cung cấp liên quan đến nhân viên (nếu có)
        $deleteSupplier = "DELETE FROM supplier WHERE ECode = :employee_id";
        $stmt = $conn->prepare($deleteSupplier);
        $stmt->execute([':employee_id' => $employee_id]);

        // Xóa các khách hàng liên quan đến nhân viên (nếu có)
        $deleteCustomer = "DELETE FROM customer WHERE ECode = :employee_id";
        $stmt = $conn->prepare($deleteCustomer);
        $stmt->execute([':employee_id' => $employee_id]);

        // Xóa bản ghi nhân viên trong bảng `employee`
        $deleteEmployee = "DELETE FROM employee WHERE ECode = :employee_id";
        $stmt = $conn->prepare($deleteEmployee);
        $stmt->execute([':employee_id' => $employee_id]);

        // Hoàn tất giao dịch
        $conn->commit();

        // Sau khi xóa thành công, chuyển hướng về trang danh sách nhân viên
        header('Location: employees.php');
        exit;
    } catch (PDOException $e) {
        // Hủy giao dịch nếu có lỗi
        $conn->rollBack();
        echo "Error: Không thể xóa nhân viên. " . $e->getMessage();
    }
} else {
    echo "Không có nhân viên nào được chọn để xóa.";
}
ob_end_flush();
?>
