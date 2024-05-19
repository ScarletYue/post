<?php
$username = "root";
$password = "";      
$server   = "localhost";  
$dbname   = "webgame";   

// Kết nối database
$connect = new mysqli($server, $username, $password, $dbname);

// Kiểm tra kết nối
if ($connect->connect_error) {
    die("Kết nối không thành công: " . $connect->connect_error);
}

// Xử lý thêm tin tức
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_add"])) {
    $name = $_POST['name'];
    $loainame = $_POST['loainame'];
    $mota = $_POST['mota'];
    $mota2 = $_POST['mota2'];
    $mota3 = $_POST['mota3'];

    // Xử lý tải hình ảnh chính lên
    $image = $_FILES["image"]["name"];
    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Xử lý tải hình ảnh phụ lên
    $image2 = isset($_FILES["image2"]) ? $_FILES["image2"]["name"] : '';
    if (!empty($image2)) {
        $target_dir_image2 = "img/";
        $target_file_image2 = $target_dir_image2 . basename($_FILES["image2"]["name"]);
        move_uploaded_file($_FILES["image2"]["tmp_name"], $target_file_image2);
    }

    // Xử lý tải hình ảnh phụ lên
    $image3 = isset($_FILES["image3"]) ? $_FILES["image3"]["name"] : '';
    if (!empty($image3)) {
        $target_dir_image3 = "img/";
        $target_file_image3 = $target_dir_image3 . basename($_FILES["image3"]["name"]);
        move_uploaded_file($_FILES["image3"]["tmp_name"], $target_file_image3);
    }

    // Xử lý tải video lên
    $video = isset($_FILES["video"]) ? $_FILES["video"]["name"] : '';
    if (!empty($video)) {
        $target_dir_video = "videos/";
        $target_file_video = $target_dir_video . basename($_FILES["video"]["name"]);
        move_uploaded_file($_FILES["video"]["tmp_name"], $target_file_video);
    }
    
    $sql = "INSERT INTO tintuc (name, loainame, image, image2, image3, video, mota, mota2, mota3) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssssssss", $name, $loainame, $image, $image2, $image3, $video, $mota, $mota2, $mota3);

    // Thực thi câu lệnh đã chuẩn bị với các giá trị đã bind
    if ($stmt->execute()) {
        echo "";
    } else {
        echo "Lỗi khi thêm tin tức: " . $stmt->error;
    }
}

// Xử lý xóa tin tức
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_delete"])) {
    $tintuc_name = $_POST["tintuc_name"];
    
    // Xóa tin tức từ cơ sở dữ liệu
    $sql = "DELETE FROM tintuc WHERE name='$tintuc_name'";
    if ($connect->query($sql) === TRUE) {
        echo "";
    } else {
        echo "Lỗi khi xóa tin tức: " . $connect->error;
    }
}

// Biến để lưu trữ dữ liệu tin tức cần chỉnh sửa
$tintuc_to_edit = null;

// Xử lý yêu cầu sửa tin tức
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_tintuc"])) {
    $tintuc_id = $_POST["tintuc_id"];
    
    // Lấy dữ liệu tin tức từ cơ sở dữ liệu
    $sql = "SELECT * FROM tintuc WHERE id=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $tintuc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $tintuc_to_edit = $result->fetch_assoc();
    }
}

// Xử lý yêu cầu cập nhật tin tức
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_edit"])) {
    $tintuc_id = $_POST["tintuc_id"];
    $new_name = $_POST["new_name"];
    $new_loainame = $_POST["new_loainame"];
    $new_mota = $_POST["new_mota"];
    $new_mota2 = $_POST["new_mota2"];
    $new_mota3 = $_POST["new_mota3"];
    
    // Xử lý tải hình ảnh mới lên
    $new_image = $_FILES["new_image"]["name"];
    $target_dir_image = "img/";
    $target_file_image = $target_dir_image . basename($_FILES["new_image"]["name"]);
    move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file_image);

    // Xử lý tải hình ảnh phụ lên
    $new_image2 = $_FILES["new_image2"]["name"];
    $target_dir_image2 = "img/";
    $target_file_image2 = $target_dir_image2 . basename($_FILES["new_image2"]["name"]);
    move_uploaded_file($_FILES["new_image2"]["tmp_name"], $target_file_image2);

    // Xử lý tải hình ảnh phụ lên
    $new_image3 = $_FILES["new_image3"]["name"];
    $target_dir_image3 = "img/";
    $target_file_image3 = $target_dir_image3 . basename($_FILES["new_image3"]["name"]);
    move_uploaded_file($_FILES["new_image3"]["tmp_name"], $target_file_image3);

    // Xử lý tải video lên
    $new_video = $_FILES["new_video"]["name"];
    $target_dir_video = "videos/";
    $target_file_video = $target_dir_video . basename($_FILES["new_video"]["name"]);
    move_uploaded_file($_FILES["new_video"]["tmp_name"], $target_file_video);

    // Cập nhật thông tin tin tức trong cơ sở dữ liệu
    $sql = "UPDATE tintuc SET name=?, loainame=?, mota=?, mota2=?, mota3=?, image=?, image2=?, image3=?, video=? WHERE id=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssssssssi", $new_name, $new_loainame, $new_mota, $new_mota2, $new_mota3, $new_image, $new_image2, $new_image3, $new_video, $tintuc_id);

    if ($stmt->execute()) {
        echo "<p>Sửa tin tức thành công</p>";
    } else {
        echo "<p>Lỗi khi sửa tin tức: " . $stmt->error . "</p>";
    }
}

// Đóng kết nối
$connect->close();
?>      

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tin Tức</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.tiny.cloud/1/pr34g2xvlk7l80bb0m6ok7g7uqffxm2l9zcgwvu0isfh093m/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/script.js" defer></script>

</head>
<body>
    <?php include 'includes/header_admin.php'; ?>

    <div class="nav">
        <a href="#them-tin-tuc">Thêm Tin Tức</a>
        <a href="#xoa-tin-tuc">Xóa Tin Tức</a>
        <a href="#sua-tin-tuc">Sửa Tin Tức</a>
    </div>

    <h1 class="h1">Quản Lý Tin Tức</h1>
    <div class="grip">
        <div id="them-tin-tuc" class="container">
            <h2 class="h2">Thêm Tin Tức Thông Tin</h2>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_add"])): ?>
                <p><?php echo "Thêm tin tức thành công"; ?></p>
            <?php endif; ?>
            <form action="" method="post" enctype="multipart/form-data" class="form">
                <label for="name">Tên Tin Tức:</label>
                <input class="text" type="text" id="name" name="name" placeholder="Tên Tin Tức" required><br><br>

                <label for="loainame">Loại Tin Tức:</label>
                <input class="text" type="text" id="loainame" name="loainame" placeholder="Loại Tin Tức" required><br><br>

                <label for="image">Hình Ảnh:</label>
                <input class="file" type="file" id="image" name="image" accept="image/*" required><br><br>

                <label for="mota">Mô tả:</label><br>
                <textarea id="mota" name="mota" placeholder="Mô Tả 1"></textarea><br><br>

                <label for="image2">Hình Ảnh Phụ 1:</label>
                <input class="file" type="file" id="image2" name="image2" accept="image/*"><br><br>

                <label for="mota2">Mô tả 2:</label><br>
                <textarea id="mota2" name="mota2" placeholder="Mô Tả 2"></textarea><br><br>

                <label for="image3">Hình Ảnh Phụ 2:</label>
                <input class="file" type="file" id="image3" name="image3" accept="image/*"><br><br>

                <label for="mota3">Mô tả 3:</label><br>
                <textarea id="mota3" name="mota3" placeholder="Mô Tả 3"></textarea><br><br>

                <label for="video">Video:</label>
                <input class="file" type="file" id="video" name="video" accept="video/*"><br><br>

                <button class="submit" type="submit" name="submit_add">Thêm Tin Tức</button>
            </form>
        </div>

        <div id="xoa-tin-tuc" class="container">
            <h2 class="h2">Xóa Tin Tức</h2>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_delete"])): ?>
                <p><?php echo "Xóa Tin Tức thành công"; ?></p>
            <?php endif; ?>
            <form action="" method="post" class="form">
                <label for="tintuc_name">Nhập tên tin tức để xóa:</label>
                <input class="text" type="text" id="tintuc_name" name="tintuc_name" placeholder="Tên Tin Tức" required><br><br>
                <button class="submit" type="submit" name="submit_delete">Xóa Tin Tức</button>
            </form>
        </div>

        <?php if ($tintuc_to_edit): ?>
        <div id="sua-tin-tuc" class="container">
            <h2 class="h2">Sửa Tin Tức Thông Tin</h2>
            <form action="" method="post" enctype="multipart/form-data" class="form">
                <input type="hidden" name="tintuc_id" value="<?php echo $tintuc_to_edit['id']; ?>">

                <label for="new_name">Tên mới:</label>
                <input class="text" type="text" id="new_name" name="new_name" value="<?php echo $tintuc_to_edit['name']; ?>" required><br><br>

                <label for="new_loainame">Loại mới:</label>
                <input class="text" type="text" id="new_loainame" name="new_loainame" value="<?php echo $tintuc_to_edit['loainame']; ?>" required><br><br>
                
                <label for="new_image">Hình Ảnh Chính mới:</label><br>
                <input class="file" type="file" id="new_image" name="new_image" accept="image/*" required><br><br>

                <label for="new_mota">Mô tả:</label><br>
                <textarea id="new_mota" name="new_mota"><?php echo $tintuc_to_edit['mota']; ?></textarea><br><br>

                <label for="new_image2">Hình Ảnh Phụ 1:</label>
                <input class="file" type="file" id="new_image2" name="new_image2" accept="image/*"><br><br>

                <label for="new_mota2">Mô tả 2:</label><br>
                <textarea id="new_mota2" name="new_mota2"><?php echo $tintuc_to_edit['mota2']; ?></textarea><br><br>

                <label for="new_image3">Hình Ảnh Phụ 2:</label>
                <input class="file" type="file" id="new_image3" name="new_image3" accept="image/*"><br><br>

                <label for="new_mota3">Mô tả 3:</label><br>
                <textarea id="new_mota3" name="new_mota3"><?php echo $tintuc_to_edit['mota3']; ?></textarea><br><br>

                <label for="new_video">Video:</label>
                <input class="file" type="file" id="new_video" name="new_video" accept="video/*"><br><br>

                <button class="submit" type="submit" name="submit_edit">Sửa Tin Tức</button>
            </form>
        </div>
    <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#mota, #new_mota, #mota2, #new_mota2, #mota3, #new_mota3',
                plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                toolbar_mode: 'floating',
            });
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
</body>
</html>
