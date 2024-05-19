<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/index.css?v=<?php echo time(); ?>">

<div class="video-hero">
  <video id="video" controls autoplay muted loop>
    <source src="assets\img\Genshin Impact - Đi vào thế giới thần kỳ đầy mạo hiểm..mp4" type="video/mp4">
    Your browser does not support the video tag.
  </video>
  <div class="overlay"></div>
  <button id="toggleButton" class="play-button">⏸️</button>
</div>


<link rel="stylesheet" href="assets/css/tintuc.css?v=<?php echo time(); ?>">
<style> 
    .nen {
    position: relative;
    min-height: 100vh; 
    font-family: Arial, sans-serif; 
}

/* Hình nền mờ */
.nen::after {
    content: "";
    background-image: url('assets/css/nentintuc2.jpg'); 
    position: absolute;
    top: -10%;
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
    font-size: 40px;
    font-weight: bold;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
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

<div class="gioithieu">
    <h3 class="h3"><img src='assets/img/tintuc1.png'> &nbsp;Tính năng đặc sắc &nbsp;<img src='assets/img/tintuc2.png'></h3>
  <section class="hero">
      <div class="hero-content">
          <h1>Cuộc phiêu lưu giả tưởng đậm chất sử thi</h1>
          <p>Trải nghiệm cảm giác mạo hiểm cá nhân. Trong vai trò là Nhà Lữ Hành đến từ thế giới khác, bạn bước vào hành trình tìm lại chính mình và người thân mất tích.</p>
      </div>
      <div class="hero-image">
          <img src="assets/img/common.jpg" alt="Nhân vật">
      </div>
  </section>

  <section class="hero">
      <div class="hero-image">
          <img src="assets/img/common1.jpg" alt="Nhân vật">
      </div>
      <div class="hero-content">
          <h1>Khám phá thế giới của riêng bạn</h1>
          <p>Bay qua đại lục rộng lớn, vẫy vùng cùng sóng nước, vượt qua núi non trùng điệp. Đặt chân đến những nơi vắng bóng người, khám phá hết thảy những điều kỳ bí đang ẩn giấu trong thế giới tràn đầy kỳ tích này.</p>
      </div>
  </section>

  <section class="hero">
      <div class="hero-content">
          <h1>Khám phá một mình hoặc cùng nhau chiến đấu</h1>
          <p>Bạn có thể chiến đấu một mình, hoặc mời bạn bè tham gia tổ đội 4 người đánh bại những kẻ thù khó nhằn (PS4, iOS, Android, PC)</p>
      </div>
      <div class="hero-image">
          <img src="assets/img/common3.jpg" alt="Nhân vật">
      </div>
  </section>

  <section class="hero">
      <div class="hero-image">
          <img src="assets/img/common2.jpg" alt="Nhân vật">
      </div>
      <div class="hero-content">
          <h1>Điều khiển bảy loại nguyên tố</h1>
          <p>Không đơn giản là game chiến đấu thông thường, trong thế giới mở của Genshin Impact, bạn có thể linh hoạt, khéo léo sử dụng sức mạnh nguyên tố đánh bại cường địch và giải mã những câu đố phức tạp.</p>
      </div>
  </section>

  <section class="hero">
      <div class="hero-content">
          <h1>Xây dựng đội ngũ của riêng mình</h1>
          <p>Chọn người đồng hành cùng bạn. Với hơn 20 nhân vật (còn tiếp tục cập nhật) cùng những sức mạnh, cá tính và phong cách chiến đấu riêng biệt, hãy chọn thành viên phù hợp với đội hình của bạn.</p>
      </div>
      <div class="hero-image">
          <img src="assets/img/common4.jpg" alt="Nhân vật">
      </div>
  </section>
</div>
<div class="gioithieu">
    <h3 class="h3"><img src='assets/img/tintuc1.png'> &nbsp;Đồ họa &nbsp;<img src='assets/img/tintuc2.png'></h3>
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="10000">
            <img src="assets/img/dohoa1.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item" data-bs-interval="2000">
            <img src="assets/img/dohoa2.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item" >
            <img src="assets/img/dohoa3.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<script>
var video = document.getElementById("video");
var toggleButton = document.getElementById("toggleButton");
var isPlaying = true;

toggleButton.addEventListener("click", function() {
  if (isPlaying) {
    video.pause();
    toggleButton.innerHTML = "▶️";
  } else {
    video.play();
    toggleButton.innerHTML = "⏸️";
  }
  isPlaying = !isPlaying;
});

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
