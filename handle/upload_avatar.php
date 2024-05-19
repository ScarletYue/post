<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "Không tìm thấy người dùng.";
        exit; 
    }

    $target_dir = "uploads/";

    if (!isset($_FILES["fileToUpload"]["name"]) || empty($_FILES["fileToUpload"]["name"])) {
        echo "Bạn chưa chọn file để upload.";
        exit; 
    }

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check === false) {
        echo "File bạn tải lên không phải là ảnh.";
        exit; 
    }

    // Kiểm tra kích thước file
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Kích thước file quá lớn.";
        exit; 
    }

    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Chỉ được phép tải lên các định dạng JPG, JPEG, PNG, GIF.";
        exit; 
    }

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $username = "root"; 
        $password = "";      
        $server   = "localhost"; 
        $dbname   = "webgame";  

        $connect = new mysqli($server, $username, $password, $dbname);

        if ($connect->connect_error) {
            die("Connection failed: " . $connect->connect_error);
        }
        if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; 
        $avatar_path = $target_file;
        $sql = "UPDATE login SET avatar=? WHERE id=?";

        $stmt = $connect->prepare($sql);
        $stmt->bind_param("si", $avatar_path, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Avatar đã được tải lên thành công.";
        } else {
            echo "Lỗi khi cập nhật avatar: " . $connect->error;
        }
        $stmt->close();
        $connect->close();
    } else {
        echo "Có lỗi xảy ra khi tải lên file của bạn.";
    }
}
}
?>
