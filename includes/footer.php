<link rel="stylesheet" href="assets/css/footer.css?v=<?php echo time(); ?>">

<footer>
    <div class="footer">
            <div class="footer1">
              <img class="logo1" src="assets\img\Genshin_icon.png">
              <p>Hotline: <a href="tel:1900 636 452" style = "text-decoration: none; color: #ff9999">1900 636 452</a> </p>
              <p>Email:<a href="mailto:genshincs_vn@hoyoverse.com" style = "text-decoration: none; color: #ff9999"> genshincs_vn@hoyoverse.com</a></p>
              <p>Chơi quá 180 phút một ngày sẽ ảnh hưởng xấu đến sức khỏe</p>
              <img src="assets\img\hoyoverse-game-logo-png-3.png" class="linklogo"> 
            </div>
            <div>
              <p class="tieude" style="font-size: 20px;">Đăng ký thành viên</p>
              <?php if(isset($_SESSION['is_users']) && $_SESSION['is_users']): ?>
                <!-- Nếu đăng nhập với tư cách users, hiển thị nút "Quản lý" và "Đăng xuất" -->
                <?php if (isset($_SESSION['is_users']) && $_SESSION['is_users']): ?>
                <?php endif; ?>
                <a href="index.php?logout=true" class="btn button-container" style="background-color: #270071; color : white; font-weight : bold; margin-right: 5px; font-size: 17px; margin-bottom: 5%; margin-top: -2%;">Đăng xuất</a>
            <?php else: ?>
              <a href ="signup.php"><button class="btn button-container" style="background-color: #270071; color : white; font-weight : bold; margin-right: 5px; font-size: 15px; margin-bottom: 5%; margin-top: -2%;" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng ký</button></a>
              <?php endif; ?>              
              <p style=" margin-left: -10%;">Đăng ký với chúng tôi để nhận email về tin tức mới</p>
            </div>
    </div>
  </footer>
    <div class="footer2">
    <div class="container2">
      <p><?php echo date("Y"); ?> All rights reserved. Genshin Impact <img class="logo2" src="assets\img\logo.png"></p>
    </div>
    </div>