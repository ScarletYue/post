
<?php
session_start(); // Bắt đầu phiên làm việc

// Kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ../signup.php');
    exit;
}

// Kiểm tra phương thức POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $noidung = ($_POST['content']);

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

    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO posts (user_id, noidung) VALUES (?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $noidung);

    if ($stmt->execute()) {
        echo "<p style='font-size: 30px; color: #270071; font-weight: bold; text-align: center; margin-top: 10%; margin-bottom: -10%;'>Bài viết này đã đăng thành công!</p>";
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    header('Location: ../trangpost.php#post'.$post_id);
    $conn->close();
} else {
    echo "Phương thức không hợp lệ!";
}
?>
<style> 
    .back-btn-container {
        display: flex; 
        justify-content: center;
        align-items: center; 
        height: 100vh; 
    }

    .back-btn {
        background-color: #270071; 
        border: none; 
        color: white; 
        padding: 15px 30px; 
        text-align: center; 
        text-decoration: none; 
        font-size: 16px; 
        cursor: pointer; 
        transition: background-color 0.3s, color 0.3s; 
        border-radius: 8px; 
    }

    .back-btn:hover {
        background-color: #704ba1; 
    }

    .back-btn:active {
        background-color: #40008f; 
    }

    .back-btn:disabled {
        opacity: 0.6; 
        cursor: not-allowed; 
    }

    @media screen and (max-width: 768px) {
        .back-btn {
            font-size: 14px; 
            padding: 10px 20px;
        }
    }
</style>
<div class="back-btn-container">
    <button class="back-btn" onclick="goBack()">Trở lại</button>
</div>
<script>
function goBack() {
    window.history.back();
}
</script>