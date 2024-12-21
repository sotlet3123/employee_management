<?php
include 'db_connection.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}

$search_query = isset($_GET['search_query']) ? trim($_GET['search_query']) : '';
$search_param = '%' . $search_query . '%';

// Truy vấn danh sách nhân viên
$sql = "SELECT * FROM employees WHERE name LIKE ? OR id LIKE ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$result = $stmt->get_result();

// Tạo HTML bảng
$tableHTML = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tableHTML .= "<tr>";
        $tableHTML .= "<td>" . htmlspecialchars($row['id']) . "</td>";
        $tableHTML .= "<td>" . htmlspecialchars($row['name']) . "</td>";
        $tableHTML .= "<td>" . htmlspecialchars($row['email']) . "</td>";
        $tableHTML .= "<td>" . htmlspecialchars($row['position']) . "</td>";
        $tableHTML .= "<td class='text-center'>";
        $tableHTML .= "<a href='edit_employee.php?id=" . $row['id'] . "' class='btn btn-sm btn-success mx-1'>Sửa</a>";
        $tableHTML .= "<a href='delete_employee.php?id=" . $row['id'] . "' onclick='return confirm(\"Bạn có chắc chắn muốn xóa nhân viên này?\");' class='btn btn-sm btn-danger mx-1'>Xóa</a>";
        $tableHTML .= "<a href='set_salary.php?id=" . $row['id'] . "' class='btn btn-sm btn-primary mx-1'>Tính Lương</a>";
        $tableHTML .= "</td></tr>";
    }
} else {
    $tableHTML .= "<tr><td colspan='5' class='text-center'>Không có nhân viên nào.</td></tr>";
}

// Đếm tổng số nhân viên
$total_employees = $result->num_rows;

// Trả về JSON
echo json_encode(['tableHTML' => $tableHTML, 'total' => $total_employees]);

$stmt->close();
$conn->close();
