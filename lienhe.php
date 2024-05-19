<?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets\css\lienhe.css?v=<?php echo time(); ?>">

</head>

<h2 class="contact-heading">Liên Hệ Tư Vấn</h2>
<div class="container-lienhe">
    <div>
        <div class="address-container">
            <b>Trang web nhà phát hành</b>
            <p><a href="https://genshin.hoyoverse.com/vi/home"><img src='assets/img/Genshin_icon.png' alt='Genshin' style="width: 100px; height: auto;"></a> -
            <a href="https://www.hoyolab.com/home"><img src='assets/img/Hoyolap_icon.png' alt='Hoyolab' style="width: 100px; height: auto;"></a></p>
            <p>Hotline: <a href="tel:1900 636 452" style = "text-decoration: none; color: #ff9999">1900 636 452</a> </p>
            <p>Email:<a href="mailto:genshincs_vn@hoyoverse.com" style = "text-decoration: none; color: #ff9999"> genshincs_vn@hoyoverse.com</a></p>
        </div>
    </div>
    <div class="contact-container">
        <h2 class="heading">Liên hệ với chúng tôi</h2>
        <form class="contact-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">  

            <label for="fullname" class="contact-label">Họ và Tên:</label>
            <input type="text" id="fullname" name="fullname" class="contact-input" placeholder="Họ và Tên" required>

            <label for="email" class="contact-label">Email:</label>
            <input type="email" id="email" name="email" class="contact-input" placeholder="Email" required>

            <label for="phone" class="contact-label">Số Điện Thoại:</label>
            <input type="tel" id="phone" name="phone" class="contact-input" placeholder="Số Điện Thoại" required>

            <label for="message" class="contact-label">Nội Dung:</label>
            <textarea id="message" name="message" class="contact-textarea" placeholder="Nội Dung" required></textarea>

            <button name= "btn" type="submit" class="contact-submit">Gửi</button>
        </form>
    </div>
</div>
<?php
// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Lấy dữ liệu từ form
    $tenuser = $_POST["fullname"];
    $email = $_POST["email"];
    $sodienthoai = $_POST["phone"];
    $noidung = $_POST["message"];

    // Chuẩn bị câu truy vấn SQL để chèn dữ liệu vào cơ sở dữ liệu
    $sql = "INSERT INTO lienhe (tenuser, email, sodienthoai, noidung) VALUES ('$tenuser', '$email', '$sodienthoai', '$noidung')";

    if ($conn->query($sql) === TRUE) {
        // Khởi tạo đối tượng PHPMailer
        require 'mail/PHPMailer/src/Exception.php';
        require 'mail/PHPMailer/src/PHPMailer.php';
        require 'mail/PHPMailer/src/SMTP.php';

        // Tạo một đối tượng PHPMailer mới
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        try {
            // Cài đặt các thông tin server
            $mail->isSMTP();                                            
            $mail->CharSet    = 'UTF-8';
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'yenconguyet@gmail.com';                     
            $mail->Password   = 'yrgnbqyujvctxxkx';                               
            $mail->SMTPSecure = 'ssl';            
            $mail->Port       = 465;       

            $mail->setFrom('yenconguyet@gmail.com', 'Genshin Impact');
            // Thêm địa chỉ email của người liên hệ
            $mail->addAddress($email, $tenuser); 
            $mail->addAddress('yenconguyet@gmail.com', 'Người chơi liên hệ'); 

            // Thiết lập nội dung thư
            $mail->isHTML(true);                                 
            $mail->Subject = 'Thư Liên hệ';
            $noidungthu = "<h2>Thư liên hệ </h3>
            <strong style='font-size: 30px; font-weight: bold; color:#270071; '><p>Tên của bạn: " . $tenuser . " </p></strong>
            <p style='font-size: 30px; font-weight: bold; color:#270071; '>Email: " . $email . "</p>
            <p style='font-size: 30px; font-weight: bold; color:#270071; '>Số điện thoại: " . $sodienthoai . "</p>
            <p style='font-size: 30px; font-weight: bold; color:#270071;'>Nội dung: " . $noidung . "</p><br>";
            $mail->Body    = $noidungthu;
            
            // Gửi thư
            $mail->send();
            echo 'Đã gửi liên hệ';
        } catch (Exception $e) {
            echo "Liên hệ không gửi được. Lỗi: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Đóng kết nối
    $conn->close();
}
?>

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