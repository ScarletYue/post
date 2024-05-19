<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webgame";

// Kết nối database
$connect = new mysqli($servername, $username, $password, $dbname);

if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
}

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    $sql = "SELECT comments.*, login.username AS commenter_username
            FROM comments 
            INNER JOIN login ON comments.user_id = login.id
            WHERE comments.post_id = ?
            ORDER BY comments.created_at DESC";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='comment'>";
            echo "<p><strong>" . htmlspecialchars($row['commenter_username']) . "</strong>: " . htmlspecialchars($row['noidung']) . "</p>";
            echo "</div>";
        }
    } 
    $stmt->close();
} else {
    echo "Không tìm thấy post_id.";
}

$connect->close();
?>
