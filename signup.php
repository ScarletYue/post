<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/login.css?v=<?php echo time(); ?>">
<?php
// Kết nối đến cơ sở dữ liệu MySQL
$username = "root"; // Khai báo username
$password = "";      // Khai báo password
$server   = "localhost";   // Khai báo server
$dbname   = "webgame";   // Khai báo database

// Kết nối database
$connect = new mysqli($server, $username, $password, $dbname);

// Kiểm tra kết nối
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username= htmlspecialchars($_POST['username']);
    $password = ($_POST['password']); 
    $ngaysinh = ($_POST['ngaysinh']);
    $email = htmlspecialchars($_POST['email']);
    $sodienthoai = htmlspecialchars($_POST['sodienthoai']);
    $diachi = htmlspecialchars($_POST['diachi']);

    if ($_POST['password'] !== $_POST['repassword']) {
        echo "<p>Mật khẩu và nhập lại mật khẩu không khớp!</p>";
    } else {
        $stmt = $connect->prepare("INSERT INTO login (username, password, ngaysinh, email, sodienthoai, diachi, dangnhap) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $username, $password, $ngaysinh, $email, $sodienthoai, $diachi, $dangnhap);        
        $dangnhap = 1; 


        if ($stmt->execute()) {
            echo "<p>Đăng ký thành công!</p>";
        } else {
            echo "<p>Có lỗi xảy ra. Vui lòng thử lại sau.</p>";
        }

        $stmt->close();
    }
}
$connect->close();
?>

<body>
    <div class="container12">
        <h2 class="dk">Đăng ký tài khoản</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label class="label" for="username">Họ và tên:</label><br>
            <input class= "text" type="text" id="username" name="username" placeholder="Họ và Tên" required><br>
            <label class="label" for="ngaysinh">Ngày sinh:</label><br>
            <input class = "date" type="date" id="ngaysinh" name="ngaysinh" placeholder="Ngày Sinh" required><br>
            <label class="label" for="email">Email:</label><br>
            <input class= "email" type="email" id="email" name="email" placeholder="Email" required><br>
            <label class="label" for="sodienthoai">Số điện thoại:</label><br>
            <input class= "text" type="text" id="sodienthoai" name="sodienthoai" placeholder="Số Điện Thoại" required><br>
            <label class="label" for="diachi">Địa chỉ:</label><br>
            <textarea class= "textarea"id="diachi" name="diachi" placeholder="Địa chỉ" required></textarea><br>
            <label class="label" for="password">Mật khẩu:</label><br>
            <input class= "password" type="password" id="password" name="password" placeholder="Mật Khẩu" required><br>
            <label class="label" for="repassword">Nhập lại mật khẩu:</label><br>
            <input class="password" type="password" id="repassword" name="repassword" placeholder="Nhập Lại Mật Khẩu" required><br>

            <input class= "submit" type="submit" value="Đăng ký">
        </form>
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