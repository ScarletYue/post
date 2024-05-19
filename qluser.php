<?php include 'includes/header_admin.php'; ?>
<link rel="stylesheet" href="assets/css/qluser.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">

<div class="container">
<?php
session_start();

// Kết nối tới cơ sở dữ liệu
$connect = new mysqli("localhost", "root", "", "webgame");
if ($connect->connect_error) {
    die("Kết nối không thành công: " . $connect->connect_error);
}

// Lấy thông tin tất cả người dùng từ bảng login
$sql = "SELECT * FROM login";
$result = $connect->query($sql);

$stt=1;
if ($result->num_rows > 0) {
    echo "<h2>Danh sách người dùng</h2>";
    echo "<table>";
    echo "<tr>
            <th>Số thứ tự</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Ngày đăng ký</th>
            <th>Lần đăng nhập</th>
        </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $stt . "</td>";
        echo "<td>" . $row["username"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["sodienthoai"] . "</td>";
        echo "<td>" . $row["diachi"] . "</td>";
        echo "<td>" . $row["ngaydangky"] . "</td>";
        echo "<td>" . $row["dangnhap"] . "</td>";
        echo "</tr>";
        $stt++;
    }
    echo "</table>";
} else {
    echo "Không có người dùng nào.";
}

$connect->close();
?>
</div>
<?php include 'includes/footer.php'; ?>
<script>
function createSnowflake() {
    const snowflake = document.createElement('div');
    snowflake.classList.add('snowflake');
    snowflake.style.left = Math.random() * window.innerWidth + 'px';
    snowflake.style.animationDuration = Math.random() * 5 + 3 + 's';
    snowflake.style.opacity = Math.random();
    snowflake.innerHTML = '❄';
    document.body.appendChild(snowflake);

    setTimeout(() => {
        snowflake.remove();
    }, 5000);
    }
    setInterval(createSnowflake, 100);
</script>