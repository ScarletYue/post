<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/post.css?v=<?php echo time(); ?>">
    <script src="https://cdn.tiny.cloud/1/pr34g2xvlk7l80bb0m6ok7g7uqffxm2l9zcgwvu0isfh093m/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/script.js" defer></script>

    <title>Đăng bài</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h2>Đăng bài viết mới</h2>
        <form method="post" action="handle/post.php">
            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" placeholder="Nội Dung"></textarea><br><br>
            </div>
            <div class="form-group">
                <button class="submit" type="submit">Đăng bài</button>
            </div>
        </form>
    </div>

    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
        });
    </script>
</body>
</html>

<?php include 'includes/footer.php'; ?>