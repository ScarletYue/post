<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/chitiet.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">

<div class="chitiet">
    <?php
    $username = "root";
    $password = "";
    $server = "localhost";
    $dbname = "webgame";

    $connect = new mysqli($server, $username, $password, $dbname);
    if ($connect->connect_error) {
        die("Kết nối không thành công: " . $connect->connect_error);
    }

    if (isset($_GET['id'])) {
        $product_id = $_GET['id'];

        $sql = "SELECT * FROM tintuc WHERE id = $product_id";
        $result = $connect->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            echo "<div class='tin-tuc'>";
            echo "<h2>" . $row["name"] . "</h2>";
            echo "<p><strong>Thời gian đăng: </strong>" . $row["created_at"] . "</p>";
            echo "<div class='hinh-anh'><img src='img/" . $row["image"] . "' alt=''></div>";
            echo "<p>" . $row["mota"] . "</p>";
            if (!empty($row["image2"])) {
                echo "<div class='hinh-anh'><img src='img/" . $row["image2"] . "' alt=''></div>";
                echo "<p>" . $row["mota2"] . "</p>";
            }
            if (!empty($row["image3"])) {
                echo "<div class='hinh-anh'><img src='img/" . $row["image3"] . "' alt=''></div>";
                echo "<p>" . $row["mota3"] . "</p>";
            }
            if (!empty($row["video"])) {
                echo "<div class='video'><video controls><source src='videos/" . $row["video"] . "' type='video/mp4'>Trình duyệt của bạn không hỗ trợ video.</video></div>";
            }
            echo "</div>";
            echo "</div>";
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    } else {
        echo "Không tìm thấy id sản phẩm.";
    }

    $connect->close();
    ?>
</div>

<?php include 'tintuckhac.php'; ?>
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