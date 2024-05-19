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
    $sql_delete = "DELETE FROM likes WHERE post_id = ? AND user_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("ii", $post_id, $user_id);
    if ($stmt_delete->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $stmt_delete->error;
    }
    $stmt_delete->close();
    $conn->close();
} else {
    echo 'error: invalid request';
}
?>
