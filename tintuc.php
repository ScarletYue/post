    <link rel="stylesheet" href="assets/css/tintuc.css?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="assets/css/header.css?v=<?php echo time(); ?>">
<?php
$audioFile = "assets/img/A Transparent Moon (Liuli Pavilion).mp3";
$audioIcon = "assets/img/speaker-off.png";
$speakerOnIcon = "assets/img/speaker.png";
$speakerOffIcon = "assets/img/speaker-off.png";
?>

<?php
session_start(); // Bắt đầu phiên làm việc

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

// Kiểm tra nếu có yêu cầu đăng xuất
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Xóa tất cả các biến phiên
    $_SESSION = array();
    // Hủy phiên
    session_destroy();
    // Chuyển hướng người dùng về trang đăng nhập
    header('Location: index.php');
    exit;
}

// Kiểm tra xem đã submit form đăng nhập chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form đăng nhập
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Kiểm tra xác thực thông tin đăng nhập của admin
    if ($input_username === 'admin' && $input_password === 'admin123') {
        // Đăng nhập thành công, lưu thông tin đăng nhập vào biến phiên        
        $_SESSION['is_admin'] = true;
        // Chuyển hướng người dùng đến trang chính
        header('Location: index.php');
        exit;
    }

    // Kiểm tra xác thực thông tin đăng nhập của người dùng thông thường
    $check_login_query = "SELECT * FROM login WHERE username = '$input_username' AND password = '$input_password'";
    $login_result = $connect->query($check_login_query);

    // Xác thực thông tin đăng nhập
    if ($login_result->num_rows > 0) {
        // Lấy thông tin người dùng từ cơ sở dữ liệu
        $user_row = $login_result->fetch_assoc();
        // Lưu user_id vào biến phiên
        $_SESSION['user_id'] = $user_row['id'];
        // Cập nhật số lần đăng nhập và thời điểm đăng nhập cuối cùng
        $dangnhap = $user_row['dangnhap'] + 1;
        // Cập nhật thông tin vào bảng login
        $update_query = "UPDATE login SET dangnhap = $dangnhap WHERE username = '$input_username'";
        if ($connect->query($update_query) === TRUE) {
            // Đăng nhập thành công, lưu thông tin đăng nhập vào biến phiên        
            $_SESSION['is_users'] = true;
            // Chuyển hướng người dùng đến trang chính
            header('Location: index.php');
            exit;
        } else {
            echo "Lỗi khi cập nhật thông tin đăng nhập: " . $connect->error;
        }
    } else {
        // Nếu cặp username và password không tồn tại trong bảng login, hiển thị thông báo lỗi
        echo "Cặp username và password không tồn tại trong bảng login.";
    }
}

$connect->close();
?>


<header class="header">
  <div class="header-container">
    <div class="logo-container">
      <img src="assets/img/logo.png" alt="Game News Logo">
    </div>
    <nav>
      <ul>
        <li><a href="index.php">Trang chủ</a></li>
        <li><a href="trangpost.php">Cộng đồng</a></li>
        <li>
          <?php
          // Kết nối đến cơ sở dữ liệu MySQL
          $username = "root"; // Khai báo username
          $password = "";      // Khai báo password
          $server   = "localhost";   // Khai báo server
          $dbname   = "webgame";   // Khai báo database

          // Kết nối database
          $connect = new mysqli($server, $username, $password, $dbname);

          if ($connect->connect_error) {
              die("Kết nối thất bại: " . $connect->connect_error);
          }

          $sql = "SELECT DISTINCT loainame FROM tintuc";
          $result = $connect->query($sql);

          if ($result->num_rows > 0) {
              echo '<a class=" dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Tin tức</a>';
              echo '<ul class="dropdown-menu" style ="background-color: #382574;">';
              while ($row = $result->fetch_assoc()) {
                  echo '<li><a href="loaitintuc.php?loainame=' . urlencode($row["loainame"]) . '">' . $row["loainame"] . '</a></li>';
                  echo '<li><hr class="dropdown-divider"></li>';
              }
              echo '<li><a href="tintuc2.php">Tất cả</a></li>';
              echo '</ul>';
          } else {
              echo "Không có tin tức nào.";
          }
          $connect->close();
          ?>
        </li>

        <li><a href="lienhe.php">Liên hệ</a></li>
      </ul>
    </nav>
    <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
    <!-- Nếu đăng nhập với tư cách admin, hiển thị nút "Quản lý" và "Đăng xuất" -->
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
    <a href="loadtintuc.php"  style=" margin-left: 20px; font-size: 18px; text-decoration: none; color: white; font-weight: bold; width: 10%">Quản lý</a>
    <?php endif; ?>
    <a href="index.php?logout=true" class="btn btn-primary" style="background-color:#a290ff; margin-left: 50px; margin-top: -6px; margin-right: -50px; width: 10%">Đăng xuất</a>
  <?php elseif(isset($_SESSION['is_users']) && $_SESSION['is_users']): ?>
    <!-- Nếu đăng nhập với tư cách users, hiển thị nút "Quản lý" và "Đăng xuất" -->
    <?php if (isset($_SESSION['is_users']) && $_SESSION['is_users']): ?>
      <a href="thongtin.php"  style=" margin-left: 20px; font-size: 18px; text-decoration: none; color: white; font-weight: bold; width: 10%">Thông tin</a>
    <?php endif; ?>
    <a href="index.php?logout=true" class="btn btn-primary" style="background-color:#a290ff; margin-left: 50px; margin-top: -6px; margin-right: -50px; width: 10%">Đăng xuất</a>
<?php else: ?>
    <button class="btn btn-primary" style="background-color:#a290ff; margin-left: 50px; margin-top: -6px; margin-right: -50px; width: 10%" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">
        Đăng nhập
    </button>
<?php endif; ?>
        <!-- Modal -->
        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel" style="color:black;">Đăng nhập</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label" style="color:black;">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label" style="color:black;">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn " style = "background-color: #a290ff; color : white; font-weight : bold">Đăng nhập</button>
                            <br>
                            <b style="color:black;">Chưa có tài khoản--></b>  <a href="signup.php" class="btn btn-primary" style="background-color: #a290ff; color : white; font-weight : bold; margin-right: 5px; font-size: 15px;">Đăng ký</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'nutdang.php'; ?>
    <audio id="myAudio" src="<?php echo $audioFile; ?>"></audio>
    <div class="audio-controls">
      <button id="audioToggleButton">
        <img id="audioIcon" src="<?php echo $audioIcon; ?>" alt="Speaker">
      </button>
    </div>
  </div>
</header>
<script>
document.getElementById("audioToggleButton").addEventListener("click", function() {
  var audio = document.getElementById("myAudio");
  var audioIcon = document.getElementById("audioIcon");
  
  if (audio.paused) {
    audio.play();
    audioIcon.src = "<?php echo $speakerOnIcon; ?>";
  } else {
    audio.pause();
    audioIcon.src = "<?php echo $speakerOffIcon; ?>";
  }
});

// Mã JavaScript để chuyển hướng khi nhấp vào loại sản phẩm
document.addEventListener('DOMContentLoaded', function() {
            // Lặp qua các thẻ <a> của loại sản phẩm
            document.querySelectorAll('.dropdown-menu a').forEach(function(element) {
                // Lắng nghe sự kiện nhấp chuột
                element.addEventListener('click', function(event) {
                    // Ngăn chặn hành động mặc định của thẻ <a>
                    event.preventDefault();
                    // Lấy giá trị của thuộc tính href (URL)
                    var href = this.getAttribute('href');
                    // Chuyển hướng người dùng đến trang danh mục sản phẩm với tham số loainame trên URL
                    window.location.href = href;
                });
            });
        });
</script>

<style> 
    .nen {
    position: relative;
    min-height: 100vh; 
    font-family: Arial, sans-serif; 
}

/* Hình nền mờ */
.nen::after {
    content: "";
    background-image: url('assets/css/nentintuc.jpg'); 
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1; 
    opacity: 0.5; 
    background-size: cover; 
    filter: blur(5px); 
}
.h3 {
    text-align: center;
    font-size: 50px;
    font-weight: bold;
    color: #270071;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    margin-top: 50px;
}

</style>
<div class="nen">
    <h3 class="h3"><img src='assets/img/tintuc1.png'> &nbsp;Tin Tức &nbsp;<img src='assets/img/tintuc2.png'></h3>
    <div class="container">
    <?php
$username = "root";
$password = "";
$server = "localhost";
$dbname = "webgame";

// Kết nối đến cơ sở dữ liệu
$connect = new mysqli($server, $username, $password, $dbname);
if ($connect->connect_error) {
    die("Kết nối không thành công: " . $connect->connect_error);
}
$sql = "SELECT * FROM tintuc ORDER BY id DESC LIMIT 3";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Hiển thị danh sách tin tức
    while ($row = $result->fetch_assoc()) {
        $name = $row["name"];
        $name = implode(' ', array_slice(explode(' ', $name), 0, 10));
 
        echo "<div class='product'>";
        echo "<a href='chitiettintuc.php?id=" . $row["id"] . "' style='text-decoration: none;' >";
        echo "<img src='img/" . $row["image"] . "' alt='" . $row["name"] . "'>";
        echo "<h2> " . $name . "</h2>";
        echo "<p>" . $row["created_at"] . "</p>";
        echo "</a>";
        echo "</div>";
    }
} else {
    echo "Không có tin tức nào.";
}

// Đóng kết nối
$connect->close();
?>
    </div>
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