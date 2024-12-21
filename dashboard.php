<?php
// Đảm bảo kết nối được bao gồm
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Quản lý Nhân viên</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Lato" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<?php include 'header.php'; ?> <!-- Nhúng header.php -->
<div class="content">
    <?php include 'charts.php'; ?> <!-- Nhúng header.php -->
    <h2 class="h2">Danh sách Nhân viên</h2>
    <a href="add_employee.php" class="btn btn-primary margin-left">Thêm Nhân viên</a>


    <!-- Ô nhập tìm kiếm -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="search-input" placeholder="Nhập tên hoặc ID">
    </div>


    <!-- Bảng kết quả -->
    <div id="employeeTableContainer" class="table-responsive" style="margin-top: 20px;">
        <table class="table table-striped">
            <thead class="table-primary">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Tên</th>
                <th scope="col">Email</th>
                <th scope="col">Chức vụ</th>
                <th scope="col" class="text-center">Hành động</th>
            </tr>
            </thead>
            <tbody id="employeeTableBody">
            <!-- Nội dung sẽ được tải bằng AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Hiển thị tổng số nhân viên -->
    <div id="totalEmployees" class="total-employees">
        Tổng số nhân viên: <strong>0</strong>
    </div>
</div>

<?php include 'footer.php'; ?> <!-- Nhúng footer.php -->

<!-- Thêm thư viện jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Hàm tải danh sách nhân viên
    function loadEmployeeData(query = '') {
        $.ajax({
            url: 'load_employees.php', // File xử lý AJAX
            method: 'GET',
            data: { search_query: query },
            success: function(data) {
                const response = JSON.parse(data);
                $('#employeeTableBody').html(response.tableHTML);
                $('#totalEmployees').html('Tổng số nhân viên: <strong>' + response.total + '</strong>');
            },
            error: function() {
                alert('Có lỗi xảy ra khi tải dữ liệu nhân viên.');
            }
        });
    }

    // Lắng nghe sự kiện nhập liệu
    $('#searchInput').on('input', function() {
        const query = $(this).val(); // Lấy giá trị trong ô nhập
        loadEmployeeData(query); // Gọi AJAX mỗi khi có thay đổi
    });

    // Tải dữ liệu lần đầu
    loadEmployeeData();
</script>
</body>
</html>
