<?php
// delete_timekeeping.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM timekeeping WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Xóa chấm công thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }

    $conn->close();
    header("Location: timekeeping_management.php"); // Quay lại trang quản lý chấm công
    exit();
}
?>
