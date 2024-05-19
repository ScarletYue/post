<link rel="stylesheet" href="assets/css/tintuckhac.css?v=<?php echo time(); ?>">

<div class="product-container">
    <h1>Mới</h1>
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
    $sql = "SELECT * FROM tintuc ORDER BY id DESC LIMIT 5";

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
