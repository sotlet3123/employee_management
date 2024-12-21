<?php
// delete_employee.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM employees WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Xóa nhân viên thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

$conn->close();
header("Location: dashboard.php"); // Quay lại trang quản lý nhân viên
exit();
?>
