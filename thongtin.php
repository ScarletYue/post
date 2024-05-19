<?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="assets/css/thongtin.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <div class="thongtin">
    <?php
    include 'handle/upload_avatar.php';
    $username = "root"; 
    $password = "";  
    $server   = "localhost"; 
    $dbname   = "webgame";  

    // Kết nối database
    $connect = new mysqli($server, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    // Kiểm tra nếu người dùng đã đăng nhập và có phiên làm việc
    if (isset($_SESSION['user_id'])) {
        // Lấy ID của người dùng từ session
        $user_id = $_SESSION['user_id']; 

        // Truy vấn cơ sở dữ liệu để lấy thông tin của người dùng
        $sql = "SELECT * FROM login WHERE id = '$user_id'";
        $result = $connect->query($sql);

        // Kiểm tra xem có dữ liệu trả về không
        if ($result->num_rows > 0) {
            // Hiển thị thông tin người dùng
            while($row = $result->fetch_assoc()) {
                echo "<div class='user-info'>";
                echo "<h2>Thông Tin</h2>";
                echo "<div class='info-content'>";
                echo "<div class='info-text'>";
                echo "<p><strong>Họ và tên:</strong> " . $row["username"] . "</p>";
                echo "<p><strong>Sinh nhật:</strong> " . $row["ngaysinh"] . "</p>";
                echo "<p><strong>Số điện thoại:</strong> " . $row["sodienthoai"] . "</p>";
                echo "<p><strong>Địa chỉ:</strong> " . $row["diachi"] . "</p>";
                echo "<p><strong>Email:</strong> " . $row["email"] . "</p>";
                echo "<p><strong>Ngày đăng kí:</strong> " . $row["ngaydangky"] . "</p>";
                echo "</div>"; 
                echo "<div class='avatar-section'>";
                echo "<form action='' method='post' enctype='multipart/form-data'>";
                echo "<h2>Avatar</h2>";
                if (!empty($row['avatar'])) {
                    echo "<div class='avatar-frame'><img src='" . $row['avatar'] . "' alt='Avatar' class='avatar'></div><br>";
                } else {
                    echo "<div class='avatar-frame'><img src='img/avatar_macdinh.jpg' alt='Default Avatar' class='avatar'></div><br>";
                }
                echo "<label for='fileToUpload' class='label-upload'><img src='assets/img/image_icon.png' alt='Chọn hình ảnh' style='width:30%'></label><br>";
                echo "<input type='file' name='fileToUpload' id='fileToUpload'><br>";
                echo "<input type='submit' value='Tải lên' name='submit' class='btn-upload'>";
                echo "</form>";

                echo "</div>"; 
                echo "</div>"; 
                echo "</div>";
                echo "</div>";

                // Truy vấn cơ sở dữ liệu để lấy thông tin các bài viết của người dùng
                $sql_posts = "SELECT posts.*, 
                            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) AS like_count, 
                            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) AS comment_count 
                    FROM posts 
                    WHERE posts.user_id = '$user_id'";
                $result_posts = $connect->query($sql_posts);

                // Hiển thị thông tin về các bài viết của người dùng
                if ($result_posts->num_rows > 0) {
                    echo "<h2 class= 'h2'>Bài Viết Của Bạn</h2>";
                    while($row_posts = $result_posts->fetch_assoc()) {
                        echo "<div class='post'>";
                        echo "<h2> Nội dung </h2>";
                        $content = $row_posts["noidung"];
                        $content_words = explode(' ', $content);
                        if (count($content_words) > 30) {
                            $trimmed_content = implode(' ', array_slice($content_words, 0, 30));
                            echo "<p>" . $trimmed_content . "...</p>";
                            echo "<button class='view-more-btn' data-post-id='{$row_posts ["id"]}'>Xem thêm</button>";
                        } else {
                            echo "<p>" . $content . "</p>";
                        }

                        // Hiển thị modal cho nội dung đầy đủ
                        echo "<div class='modal fade' id='postModal{$row_posts["id"]}'>";
                        echo "<div class='modal-dialog'>";
                        echo "<div class='modal-content' style='width: 200%; margin-left: -50%;'>";
                        echo "<div class='modal-header'>";
                        echo "<h5 class='modal-title' id='postModalLabel'>Nội dung bài viết</h5>";
                        echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close' style='margin-left: 80%;'></button>";
                        echo "</div>";
                        echo "<div class='modal-body'>";
                        echo "<p>" . $row_posts["noidung"] . "</p>";        
                        echo "</div>"; 
                        echo "</div>";
                        echo "</div>";
                        echo "</div>"; 
                        echo "<p><strong>Ngày đăng:</strong> " . $row_posts["created_at"]. "</p>";
                        
                        
                        $liked = false;
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $check_like_sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
                        $stmt_like = $connect->prepare($check_like_sql);
                        $stmt_like->bind_param("ii", $user_id, $row_posts["id"]);
                        $stmt_like->execute();
                        $check_like_result = $stmt_like->get_result();
                        if ($check_like_result->num_rows > 0) {
                            $liked = true;
                        }
                    }
                    echo "<span class='span'>";
                    echo "<div class='like-section'>";
                    echo "<button class='likebtn' data-post-id='{$row_posts["id"]}' data-action='" . ($liked ? "unlike" : "like") . "'>";
                    echo "<img src='assets/img/" . ($liked ? "unlike_icon" : "like_icon") . ".png' alt='Like' style='width: 50px; height: auto; margin-left: 20px; margin-top: 15px;'>";
                    echo "</button>";
                    echo "<span class='like-count'>{$row_posts["like_count"]}</span>";
                    echo "</div>";

                    echo "<button class='comment-btn img' onclick='toggleComment(this)'><img src='assets/img/comment_icon.png' alt='Comment' style='width: 50px; height: auto; margin-left: 20px; margin-top: -10px;'></button>";
                    echo "<div class='comment-box hidden'>";
                    echo "<form method='post' class='comment-form' data-post-id='{$row_posts["id"]}'>";
                    echo "<input type='hidden' name='post_id' value='{$row_posts["id"]}'>";
                    echo "<textarea class='comment-input' name='comment' placeholder='Nhập bình luận của bạn' required></textarea>";
                    echo "<button type='submit' class='comment-submit-btn'>Comment</button>";
                    echo "</form>";
                    echo "</div>";
                    echo "</span>";

                    echo "<div class='comments' id='comments{$row_posts["id"]}'>";
                    // Hiển thị các bình luận
                    echo "<div class='comment-box'>";
                    $comment_sql = "SELECT comments.*, login.username AS commenter_username
                                    FROM comments 
                                    INNER JOIN login ON comments.user_id = login.id
                                    WHERE comments.post_id = ?
                                    ORDER BY comments.created_at DESC";
                    $stmt_comments = $connect->prepare($comment_sql);
                    $stmt_comments->bind_param("i", $row_posts["id"]);
                    $stmt_comments->execute();
                    $comment_result = $stmt_comments->get_result();
                    $comment_count = 0;
                    if ($comment_result->num_rows > 0) {
                        echo "<div class='comment-container'>";
                        while ($comment_row = $comment_result->fetch_assoc()) {
                            echo "<div class='comment'>";
                            echo "<p><strong>" . htmlspecialchars($comment_row['commenter_username']) . "</strong>: " . htmlspecialchars($comment_row['noidung']) . "</p>";
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
        echo "<p>Bạn chưa có bài viết nào.</p>";
        }
        echo "</div>";
    }
    } else {
        echo "Không tìm thấy thông tin người dùng.";
    }
    }
    $connect->close();
    ?>
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
    snowflake.style.animationDuration = Math.random() * 10 + 3 + 's';
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
