<?php
ob_start();
include('connect.php');
include 'nav.php'; // Bao gồm thanh điều hướng nếu cần thiết

// Kiểm tra xem có ID nhân viên được truyền vào không
if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

    try {
        // Bắt đầu giao dịch để đảm bảo tính toàn vẹn của dữ liệu
        $conn->beginTransaction();

        // Xóa các bản ghi liên quan trong các bảng phụ thuộc trước khi xóa nhân viên
        $deleteCustomerCare = "DELETE FROM customer_care WHERE employee_id = :employee_id";
        $stmt = $conn->prepare($deleteCustomerCare);
        $stmt->execute([':employee_id' => $employee_id]);

        $deleteSupplierCare = "DELETE FROM supplier_care WHERE employee_id = :employee_id";
        $stmt = $conn->prepare($deleteSupplierCare);
        $stmt->execute([':employee_id' => $employee_id]);

        $deleteOrders = "DELETE FROM orders WHERE employee_id = :employee_id";
        $stmt = $conn->prepare($deleteOrders);
        $stmt->execute([':employee_id' => $employee_id]);

        // Xóa bản ghi nhân viên trong bảng `employees`
        $deleteEmployee = "DELETE FROM employees WHERE employee_id = :employee_id";
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
