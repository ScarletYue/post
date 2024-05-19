<?php include 'includes/header_admin.php'; ?>
<link rel="stylesheet" href="assets/css/qllienhe.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">

<div class="container">
    <h2 class="h2">Danh sách liên hệ</h2>

    <table>
        <tr>
            <th>STT</th>
            <th>Họ và Tên</th>
            <th>Email</th>
            <th>Số Điện Thoại</th>
            <th>Nội Dung</th>
        </tr>
        
        <?php
        // Kết nối đến cơ sở dữ liệu MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "webgame";

        // Tạo kết nối
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }

        // Truy vấn cơ sở dữ liệu để lấy thông tin liên hệ
        $sql = "SELECT * FROM lienhe";
        $result = $conn->query($sql);
        $stt=1;
        if ($result->num_rows > 0) {
            // Hiển thị thông tin liên hệ
            while($row = $result->fetch_assoc()) {
                echo "
                <tr>
                    <td>" . $stt . "</td>
                    <td>" . $row["tenuser"]. "</td>
                    <td>" . $row["email"]. "</td>
                    <td>" . $row["sodienthoai"]. "</td>
                    <td>" . $row["noidung"]. "</td>
                </tr>";
                $stt++;
            }
        } else {
            echo "<tr><td colspan='5'>Không có thông tin liên hệ nào.</td></tr>";
        }
        // Đóng kết nối
        $conn->close();
        ?>
    </table>
</div>
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
<?php include 'includes/footer.php'; ?>
