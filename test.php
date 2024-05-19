<?php include 'includes/header.php'; ?>

<link rel="stylesheet" href="assets/css/post.css?v=<?php echo time(); ?>">
<?php
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

// Truy vấn để lấy dữ liệu từ bảng posts kết hợp với thông tin từ bảng login và số lượt thích
$sql = "SELECT posts.*, login.avatar, login.username, COUNT(likes.id) AS like_count
        FROM posts 
        INNER JOIN login ON posts.user_id = login.id
        LEFT JOIN likes ON posts.id = likes.post_id
        GROUP BY posts.id
        ORDER BY posts.created_at DESC";

$result = $connect->query($sql);

if ($result->num_rows > 0) {
    // Hiển thị các bài viết
    while($row = $result->fetch_assoc()) {
        echo "<div class='post'>";
        echo "<img src='" . $row["avatar"] . "' alt='Avatar' class='avatar'>";
        echo "<h1>" . $row["username"] . "</h1>";
        echo "<p>" . $row["noidung"] . "</p>";
        

        // Thêm nút "3 chấm" cho phép người dùng thực hiện các hành động
        echo "<div class='options'>";
        echo "<button class='more-btn' onclick='toggleDropdown(this)'></button>";
        echo "<div class='dropdown-content'>";
        echo "<button class='edit-btn'>Sửa bài viết</button>";
        echo "<button class='delete-btn'>Xóa bài viết</button>";
        echo "</div>";
        echo "</div>";

        // Kiểm tra xem người dùng đã thích bài viết này chưa
        $liked = false;
        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $check_like_sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = {$row["id"]}";
            $check_like_result = $connect->query($check_like_sql);
            if($check_like_result->num_rows > 0) {
                $liked = true;
            }
        }

        // Thêm nút like hoặc unlike tùy thuộc vào trạng thái của người dùng
        if(!$liked) {
            echo "<span class='span'>";
            echo "<form method='post' action='handle/like_post.php'>";
            echo "<input type='hidden' name='post_id' value='{$row["id"]}'>";
            echo "<button type='submit' class='likeimg'><img src='assets/img/like_icon.png' alt='Like'></button>";
            echo "</form>";
        } else {
            echo "<span class='span'>";
            echo "<form method='post' action='handle/unlike_post.php'>";
            echo "<input type='hidden' name='post_id' value='{$row["id"]}'>";
            echo "<button type='submit' class='likeimg'><img src='assets/img/unlike_icon.png' alt='unLike'></button>";
            echo "</form>";
        }
        // Thêm nút comment
        echo "<button class='comment-btn' onclick='toggleComment(this)'><img src='assets/img/comment_icon.png' alt='unLike'></button>";
        echo "<div class='comment-box hidden'>";
        echo "<form method='post' action='handle/comments_post.php'>";
        echo "<input type='hidden' name='post_id' value='{$row["id"]}'>";
        echo "<textarea class='comment-input' name='comment' placeholder='Nhập bình luận của bạn'></textarea>";
        echo "<button type='submit' class='comment-submit-btn'>Submit</button>";
        echo "</form>";
        echo "</div>";
        echo "</span>";
        echo "<span class='span'>";
        echo "<p>Người thích: " . $row["like_count"] . "</p>";
        // Hiển thị các bình luận
        echo "<div class='comment-box'>";
        $comment_sql = "SELECT comments.*, login.username AS commenter_username
                        FROM comments 
                        INNER JOIN login ON comments.user_id = login.id
                        WHERE comments.post_id = '{$row["id"]}'
                        ORDER BY comments.created_at DESC"; // Lấy tất cả bình luận
        $comment_result = $connect->query($comment_sql);
        $comment_count = 0;
        if ($comment_result->num_rows > 0) {
            echo "<div class='comment-container'>";
            while ($comment_row = $comment_result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p><strong>" . $comment_row['commenter_username'] . "</strong>: " . $comment_row['noidung'] . "</p>";
                echo "</div>";
                $comment_count++;
                if ($comment_count >= 3) {
                    // Hiển thị button chỉ khi có hơn 3 bình luận
                    $remaining_comments = $comment_result->num_rows - $comment_count;
                    echo "<button class='view-all-comments-btn btn btn-primary' data-bs-toggle='modal' data-bs-target='#commentModal'>Xem tất cả $remaining_comments bình luận</button>";
                    break; // Chỉ hiển thị tối đa 3 bình luận
                }
            }
            echo "</div>";
        }
        echo "</div>";
        echo "</span>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "Không có bài viết nào.";
}

$connect->close();
?>

</div>

<?php include 'includes/footer.php'; ?>

<!-- Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="commentModalLabel">Tất cả bình luận</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Truy vấn để lấy tất cả bình luận -->
      </div>
    </div>
  </div>
</div>

<script>
function toggleComment(button) {
    var commentBox = button.nextElementSibling;
    commentBox.classList.toggle("hidden");
}

// Sự kiện khi nhấp vào nút "Xem tất cả bình luận"
document.querySelectorAll('.view-all-comments-btn').forEach(item => {
    item.addEventListener('click', event => {
        var postId = item.closest('.post').querySelector('input[name="post_id"]').value;
        loadAllComments(postId);
    });
});

// Hàm để tải tất cả các bình luận từ cơ sở dữ liệu và hiển thị chúng trong Modal
function loadAllComments(postId) {
    var modalBody = document.querySelector('#commentModal .modal-body');
    modalBody.innerHTML = ''; // Xóa nội dung cũ trước khi tải mới

    // Thực hiện yêu cầu AJAX để lấy tất cả các bình luận cho bài viết có postId
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            modalBody.innerHTML = this.responseText; // Hiển thị các bình luận trong Modal
        }
    };
    xhr.open("GET", "handle/load_comments.php?post_id=" + postId, true);
    xhr.send();
}
</script>
