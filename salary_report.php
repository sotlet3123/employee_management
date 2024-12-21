<?php
include 'db_connection.php'; // Kết nối cơ sở dữ liệu

session_start();
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php'); // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Lato" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title>Bảng Lương Nhân Viên</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
<?php include 'header.php'; ?> <!-- Nhúng header.php -->

<div class="content mt-4" style="margin-top: 20px;">
    <h2>Bảng Lương Nhân Viên</h2>

    <!-- Form để chọn tháng, năm và phương thức sắp xếp -->
    <div class="form-row col-md-6" style="margin-top: 20px;">
        <div class="col col-md-3">
            <label for="month">Tháng:</label>
            <select name="month" class="form-control" id="month">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php echo ($m == date('m')) ? 'selected' : ''; ?>><?php echo $m; ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col col-md-3">
            <label for="year">Năm:</label>
            <input type="number" name="year" class="form-control" id="year" value="<?php echo date('Y'); ?>" min="2000" max="<?php echo date('Y'); ?>">
        </div>
        <div class="col col-md-6">
            <label for="sort_order">Sắp xếp theo:</label>
            <select name="sort_order" class="form-control" id="sort_order">
                <option value="date_desc">Ngày tính lương (Giảm dần)</option>
                <option value="date_asc">Ngày tính lương (Tăng dần)</option>
                <option value="salary_desc">Lương (Cao đến Thấp)</option>
                <option value="salary_asc">Lương (Thấp đến Cao)</option>
            </select>
        </div>
    </div>

    <div class="clearfix"></div>

    <div id="salaryTableContainer" style="margin-top: 20px;">
        <!-- Khu vực hiển thị bảng lương -->
        <p class="text-center">Vui lòng chọn tháng, năm và cách sắp xếp để xem bảng lương.</p>
    </div>
</div>

<?php include 'footer.php'; ?> <!-- Nhúng footer.php -->

<script>
    // Tự động tải bảng lương khi thay đổi giá trị trong form
    $(document).ready(function () {
        function loadSalaryData() {
            const month = $('#month').val();
            const year = $('#year').val();
            const sort_order = $('#sort_order').val();

            $.ajax({
                url: 'get_salary_data.php',
                method: 'POST',
                data: { month: month, year: year, sort_order: sort_order },
                success: function (response) {
                    $('#salaryTableContainer').html(response);
                },
                error: function () {
                    $('#salaryTableContainer').html('<p class="text-danger text-center">Đã xảy ra lỗi khi tải bảng lương.</p>');
                }
            });
        }

        // Gọi hàm khi người dùng thay đổi bất kỳ thông tin nào
        $('#month, #year, #sort_order').change(loadSalaryData);

        // Tải bảng lương ban đầu
        loadSalaryData();
    });
</script>
</body>
</html>
