<?php include 'includes/header.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="assets/css/post.css?v=<?php echo time(); ?>">


<div>
    <?php
    // Kết nối đến cơ sở dữ liệu MySQL
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webgame";

    // Kết nối database
    $connect = new mysqli($servername, $username, $password, $dbname);

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
        while ($row = $result->fetch_assoc()) {
            echo "<div class='post' id='post{$row["id"]}'>";
            if (!empty($row['avatar'])) {
                echo "<div class='avatar-frame'><img src='" . $row['avatar'] . "' alt='Avatar' class='avatar'></div><br>";
            } else {
                echo "<div class='avatar-frame'><img src='img/avatar_macdinh.jpg' alt='Default Avatar' class='avatar'></div><br>";
            }
            echo "<p style='font-size: 20px; font-weight: bold; color: #1b014d; font-family: Arial, sans-serif;'>" . $row["username"] . "</p>";

            $content = $row["noidung"];
            $content_words = explode(' ', $content);
            if (count($content_words) > 30) {
                $trimmed_content = implode(' ', array_slice($content_words, 0, 30));
                echo "<p>" . $trimmed_content . "...</p>";
                echo "<button class='view-more-btn' data-post-id='{$row["id"]}'>Xem thêm</button>";
            } else {
                echo "<p>" . $content . "</p>";
            }

            echo "<div class='modal fade' id='postModal{$row["id"]}'>";
            echo "<div class='modal-dialog'>";
            echo "<div class='modal-content' style='width: 200%; margin-left: -50%;'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='postModalLabel'>Nội dung bài viết</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<p>" . $row["noidung"] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "<p><strong>Ngày đăng:</strong> " . $row["created_at"] . "</p>";

            $liked = false;
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $check_like_sql = "SELECT * FROM likes WHERE user_id = $user_id AND post_id = {$row["id"]}";
                $check_like_result = $connect->query($check_like_sql);
                if ($check_like_result->num_rows > 0) {
                    $liked = true;
                }
            }
            echo "<span class='span'>";
            echo "<div class='like-section'>";
            echo "<button class='likebtn' data-post-id='{$row["id"]}' data-action='" . ($liked ? "unlike" : "like") . "'>";
            echo "<img src='assets/img/" . ($liked ? "unlike_icon" : "like_icon") . ".png' alt='Like' style='width: 50px; height: auto; margin-left: 20px; margin-top: 15px;'>";
            echo "</button>";
            echo "<span class='like-count'>{$row["like_count"]}</span>";
            echo "</div>";

            echo "<button class='comment-btn img' onclick='toggleComment(this)'><img src='assets/img/comment_icon.png' alt='Comment' style='width: 50px; height: auto; margin-left: 20px; margin-top: -10px;'></button>";
            echo "<div class='comment-box hidden'>";
            echo "<form method='post' class='comment-form' data-post-id='{$row["id"]}'>";
            echo "<input type='hidden' name='post_id' value='{$row["id"]}'>";
            echo "<textarea class='comment-input' name='comment' placeholder='Nhập bình luận của bạn' required></textarea>";
            echo "<button type='submit' class='comment-submit-btn'><img src='assets/img/send_icon.png' alt='send' style='width: 50px; height: auto; margin-left: 20px;'></button>";
            echo "</form>";
            echo "</div>";
            echo "</span>";

            echo "<div class='comments' id='comments{$row["id"]}'>";
            // Hiển thị các bình luận
            echo "<div class='comment-box'>";
            $comment_sql = "SELECT comments.*, login.username AS commenter_username
                            FROM comments 
                            INNER JOIN login ON comments.user_id = login.id
                            WHERE comments.post_id = '{$row["id"]}'
                            ORDER BY comments.created_at DESC";
            $comment_result = $connect->query($comment_sql);
            $comment_count = 0;
            if ($comment_result->num_rows > 0) {
                echo "<div class='comment-container'>";
                while ($comment_row = $comment_result->fetch_assoc()) {
                    echo "<div class='comment'>";
                    echo "<p><strong>" . $comment_row['commenter_username'] . "</strong>: " . $comment_row['noidung'] . "</p>";
                    echo "</div>";
                    $comment_count++;
                    if ($comment_count >= 2) {
                        $remaining_comments = $comment_result->num_rows - $comment_count;
                        echo "<button class='view-all-comments-btn btn btn-primary' data-bs-toggle='modal' data-bs-target='#commentModal'>Xem tất cả $remaining_comments bình luận</button>";
                        break;
                    }
                }
                echo "</div>";
            }
            echo "</div>";
            echo "</span>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "Không có bài viết nào.";
    }

    $connect->close();
    ?>

    <!-- Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="commentModalLabel">Tất cả bình luận</h5>
            <button type="button" class="btn-close" data-bs-dismiss='modal' aria-label='Close'></button>
          </div>
          <div class="modal-body">
            <!-- Nội dung bình luận sẽ được tải vào đây bằng Ajax -->
          </div>
        </div>
      </div>
    </div>

    <script>
    function toggleComment(button) {
        var commentBox = button.nextElementSibling;
        commentBox.classList.toggle("hidden");
    }

    document.querySelectorAll('.view-more-btn').forEach(item => {
        item.addEventListener('click', event => {
            var postId = item.dataset.postId;
            var modalId = '#postModal' + postId;
            $(modalId).modal('show');
        });
    });
    //like
    $(document).ready(function() {
        $('.likebtn').click(function(e) {
            e.preventDefault();
            var button = $(this);
            var postId = button.data('post-id');
            var action = button.data('action');
            var url = action === 'like' ? 'handle/like_post.php' : 'handle/unlike_post.php';

            $.ajax({
                type: 'POST',
                url: url,
                data: { post_id: postId },
                success: function(response) {
                    if (response === 'success') {
                        if (action === 'like') {
                            button.data('action', 'unlike');
                            button.find('img').attr('src', 'assets/img/unlike_icon.png');
                        } else {
                            button.data('action', 'like');
                            button.find('img').attr('src', 'assets/img/like_icon.png');
                        }

                        var likeCountSpan = button.closest('.like-section').find('.like-count');
                        var likeCount = parseInt(likeCountSpan.text());
                        likeCountSpan.text(action === 'like' ? likeCount + 1 : likeCount - 1);
                    } else {
                        alert('Có lỗi xảy ra. Vui lòng thử lại.');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    alert('AJAX error: ' + textStatus + ' : ' + errorThrown);
                }
            });
        });
        //comment
        $('.comment-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var postId = form.data('post-id');
        var commentContent = form.find('.comment-input').val();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'handle/comments_post.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response === 'success') {
                    // Thêm bình luận mới vào danh sách hiện có
                    var newComment = '<div class="comment"><p><strong>' + formData.get('username') + '</strong>: ' + commentContent + '</p></div>';
                    $('#comments' + postId + ' .comment-box').prepend(newComment);
                    form.find('.comment-input').val('');
                } else {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                alert('AJAX error: ' + textStatus + ' : ' + errorThrown);
            }
        });
    });


        $('.comment-btn').click(function() {
            var postId = $(this).closest('.post').attr('id').replace('post', '');
            loadComments(postId);
        });
    });

    document.querySelectorAll('.view-all-comments-btn').forEach(item => {
        item.addEventListener('click', event => {
            var postId = item.closest('.post').querySelector('input[name="post_id"]').value;
            loadAllComments(postId);
        });
    });

    function loadAllComments(postId) {
        var modalBody = document.querySelector('#commentModal .modal-body');
        modalBody.innerHTML = ''; 

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                modalBody.innerHTML = this.responseText; 
            }
        };
        xhr.open("GET", "handle/load_comments.php?post_id=" + postId, true);
        xhr.send();
    }

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
</div>

<?php include 'includes/footer.php'; ?>
