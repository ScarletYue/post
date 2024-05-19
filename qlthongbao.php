<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/css/qlthongbao.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">

<?php include 'includes/header_admin.php'; ?>
<h2 class="contact-heading">Thông Báo Thông Tin Cho Khách Hàng </h2>
<div class="contact-container">
    <form class="contact-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="tintuc1" class="contact-label">Tên Tin Tức 1:</label>
        <select id="tintuc1" name="tintuc1" class="contact-select">
        <?php
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
          
        $sql = "SELECT DISTINCT name FROM tintuc ORDER BY id DESC LIMIT 3"; // Sắp xếp theo id giảm dần và giới hạn chỉ lấy 3 sản phẩm
        $result = $connect->query($sql);

        if ($result->num_rows > 0) {
                echo '<option value="">-- Chọn tin tức --</option>'; 
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
                }
            } else {
            echo '<option value="">Không có tin tức nào</option>';
        }
        $connect->close();
        ?>
    </select>
    <label for="tintuc2" class="contact-label">Tên Tin Tức 2:</label>
        <select id="tintuc2" name="tintuc2" class="contact-select" >
        <?php
        // Kết nối đến cơ sở dữ liệu MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "webgame";

        // Tạo kết nối
        $connect = new mysqli($servername, $username, $password, $dbname);

        if ($connect->connect_error) {
            die("Kết nối thất bại: " . $connect->connect_error);
        }
        if (!isset($password)) {
            $password = ""; 
        }
        
        if (!isset($dbname)) {
            $dbname = "webgame"; 
        }
        $sql = "SELECT DISTINCT name FROM tintuc ORDER BY id DESC LIMIT 3"; // Sắp xếp theo id giảm dần và giới hạn chỉ lấy 3 sản phẩm
        $result = $connect->query($sql);

        if ($result->num_rows > 0) {
                echo '<option value="">-- Chọn tin tức --</option>';
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
                }
            } else {
            echo '<option value="">Không có tin tức nào</option>';
        }
        $connect->close();
        ?>
    </select>
    <label for="tintuc3" class="contact-label">Tên Tin Tức 3:</label>
        <select id="tintuc3" name="tintuc3" class="contact-select">
        <?php
        // Kết nối đến cơ sở dữ liệu MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "webgame";

        // Tạo kết nối
        $connect = new mysqli($servername, $username, $password, $dbname);

        if ($connect->connect_error) {
            die("Kết nối thất bại: " . $connect->connect_error);
        }

        $sql = "SELECT DISTINCT name FROM tintuc ORDER BY id DESC LIMIT 3"; // Sắp xếp theo id giảm dần và giới hạn chỉ lấy 3 sản phẩm
        $result = $connect->query($sql);

        if ($result->num_rows > 0) {
                echo '<option value="">-- Chọn tin tức--</option>'; 
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row["name"] . '">' . $row["name"] . '</option>';
                }
            }else {
            echo '<option value="">Không có tin tức nào</option>';
        }
        $connect->close();
        ?>
    </select>
    <label for="message" class="contact-label">Nội Dung:</label>
        <textarea id="message" name="message" class="contact-textarea" placeholder="Nội Dung" required></textarea>

        <button name="btn" type="submit" class="contact-submit">Gửi</button>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tintuc1 = $_POST["tintuc1"];
    $tintuc2 = $_POST["tintuc2"];
    $tintuc3 = $_POST["tintuc3"];
    $message = $_POST["message"];

    // Kết nối đến cơ sở dữ liệu MySQL
    $connect = new mysqli($servername, $username, $password, $dbname);

    if ($connect->connect_error) {
        die("Kết nối thất bại: " . $connect->connect_error);
    }
    
    
    // Lấy danh sách email từ cơ sở dữ liệu
    $sql = "SELECT email FROM login";
    $result = $connect->query($sql);

    // Tạo đối tượng PHPMailer
    require 'mail/PHPMailer/src/Exception.php';
    require 'mail/PHPMailer/src/PHPMailer.php';
    require 'mail/PHPMailer/src/SMTP.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Cài đặt thông tin SMTP
        $mail->isSMTP();                                            
        $mail->CharSet    = 'UTF-8';
        $mail->Host       = 'smtp.gmail.com';                     
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'yenconguyet@gmail.com';                     
        $mail->Password   = 'yrgnbqyujvctxxkx';                               
        $mail->SMTPSecure = 'ssl';            
        $mail->Port       = 465;       

        $mail->setFrom('yenconguyet@gmail.com', 'Genshin Impact');

        // Thêm các địa chỉ email vào danh sách nhận
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $mail->addAddress($row["email"]);
            }
        }

        // Thiết lập nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Thông báo mới từ Genshin Impact';
        $mail->Body    = '<b style="font-weight: bold; font-size: 20px;">Xin chào,</b><br><br>
                        <b style="font-weight: bold; font-size: 20px;">Có một thông báo mới từ Genshin Impact về tin tức mới nhất: </b> <br><br>
                        <strong style="font-size: 30px;"> Tên tin tức 1:<br>' . $tintuc1 . ' <br> </strong> <br><br>
                        <strong style="font-size: 30px;"> Tên tin tức 2:<br>' . $tintuc2 . ' <br> </strong> <br><br>
                        <strong style="font-size: 30px;"> Tên tin tức 3:<br>' . $tintuc3 . ' <br> </strong> <br><br>
                        <strong style="font-size: 25px;">Nội dung: <br>' . $message;

        // Gửi email
        $mail->send();
        echo "Thông báo đã được gửi đi thành công!";
    } catch (Exception $e) {
        echo "Thông báo không thể gửi. Lỗi: {$mail->ErrorInfo}";
    }

    // Đóng kết nối
    $connect->close();
}
?>
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