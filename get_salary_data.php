<?php
include 'db_connection.php'; // Kết nối cơ sở dữ liệu
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}
// Nhận dữ liệu từ Ajax
$month = isset($_POST['month']) ? intval($_POST['month']) : date('m');
$year = isset($_POST['year']) ? intval($_POST['year']) : date('Y');
$sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'date_desc';

// Xác định điều kiện sắp xếp
$order_by = '';
if ($sort_order === 'date_asc') {
    $order_by = 's.calculation_date ASC';
} elseif ($sort_order === 'salary_desc') {
    $order_by = 's.total_salary DESC';
} elseif ($sort_order === 'salary_asc') {
    $order_by = 's.total_salary ASC';
} else {
    $order_by = 's.calculation_date DESC';
}

// Truy vấn bảng lương
$sql = "
    SELECT s.id, e.id AS employee_id, e.name, s.basic_salary, s.allowances, s.total_salary, s.calculation_date,
           IFNULL(SUM(TIMESTAMPDIFF(HOUR, t.check_in, t.check_out)), 0) AS total_hours
    FROM salary s
    JOIN employees e ON s.employee_id = e.id
    LEFT JOIN timekeeping t ON s.employee_id = t.employee_id
    WHERE MONTH(s.calculation_date) = ? AND YEAR(s.calculation_date) = ?
    GROUP BY s.id, e.id, e.name, s.basic_salary, s.allowances, s.total_salary, s.calculation_date
    ORDER BY $order_by";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $month, $year);
$stmt->execute();
$result = $stmt->get_result();

// Tạo bảng HTML trả về
if ($result->num_rows > 0) {
    echo '<table class="table table-striped">';
    echo '<thead><tr>';
    echo '<th>ID Nhân Viên</th><th>Tên Nhân Viên</th><th>Lương Cơ Bản</th><th>Phụ Cấp</th><th>Số Giờ Làm</th><th>Tổng Lương</th><th>Ngày Tính Lương</th>';
    echo '</tr></thead><tbody>';
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['employee_id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . number_format($row['basic_salary'], 0, ',', '.') . ' VNĐ</td>';
        echo '<td>' . number_format($row['allowances'], 0, ',', '.') . ' VNĐ</td>';
        echo '<td>' . number_format($row['total_hours'], 0, ',', '.') . ' giờ</td>';
        echo '<td>' . number_format($row['total_salary'], 0, ',', '.') . ' VNĐ</td>';
        echo '<td>' . date("d/m/Y", strtotime($row['calculation_date'])) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table>';
} else {
    echo '<p class="text-center">Không có dữ liệu bảng lương cho tháng ' . $month . ' năm ' . $year . '.</p>';
}

$stmt->close();
$conn->close();
?>
