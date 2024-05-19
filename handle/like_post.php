<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webgame";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo 'error: Connection failed';
        exit();
    }
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $sql_insert = "INSERT INTO likes (post_id, user_id) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ii", $post_id, $user_id);
        if ($stmt_insert->execute()) {
            echo 'success';
        } else {
            echo 'error: ' . $stmt_insert->error;
        }
        $stmt_insert->close();
    } else {
        echo 'error: already liked';
    }
    $stmt->close();
    $conn->close();
} else {
    echo 'error: invalid request';
}
?>
