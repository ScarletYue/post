<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $noidung = htmlspecialchars($_POST['comment']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webgame";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];

    $sql = "INSERT INTO comments (post_id, user_id, noidung) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $post_id, $user_id, $noidung);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Truy cập trái phép!";
}
?>
