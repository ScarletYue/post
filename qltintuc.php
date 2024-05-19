<?php include 'includes/header_admin.php'; ?>
<link rel="stylesheet" href="assets/css/tintuc.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">

<?php
    
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
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_tintuc"])) {
        $tintuc_id = $_POST["tintuc_id"];
        
        // Xóa tin tức từ cơ sở dữ liệu
        $sql = "DELETE FROM tintuc WHERE id=?";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $tintuc_id);
    
        if ($stmt->execute()) {
            echo "<p>Xóa tin tức thành công</p>";
        } else {
            echo "<p>Lỗi khi xóa tin tức: " . $stmt->error . "</p>";
        }
    }
    
    // Truy vấn dữ liệu từ bảng tintuc với số lượng giới hạn ban đầu
    $sql = "SELECT * FROM tintuc";
    $result = $connect->query($sql);

    // Hiển thị dữ liệu
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Chỉ trích 2 dòng từ mô tả
            $mota = $row["mota"];
            $mota = implode(' ', array_slice(explode(' ', $mota), 0, 22));
            $name = $row["name"];
            $name = implode(' ', array_slice(explode(' ', $name), 0, 10));

            echo "<div class='tintuc'>";
            echo "<a href='chitiettintuc.php?id=" . $row["id"] . "' style='text-decoration: none;' >";
            echo "<div class='tin-tuc'>";
            echo "<div class='hinh-anh'><img src='img/" . $row["image"] . "' alt=''></div>";
            echo "<div class='thong-tin'>";
            echo "<h2>" . $name . "...</h2>";
            echo "<p>" . $mota . "...</p>";
            echo "<span>";            
            echo "<form method='post' action='loadtintuc.php'>";
            echo "<input type='hidden' name='tintuc_id' value='" . $row["id"] . "'>";
            echo "<button type='submit' name='edit_tintuc' class='sx'><img src='assets/img/edit_icon.png' alt='Sửa tin tức' style='width: 50px; height: auto; margin-left: 20px; margin-top: 10px; '></button>";
            echo "</form>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='tintuc_id' value='" . $row["id"] . "'>";
            echo "<button type='submit' name='delete_tintuc' class='sx'><img src='assets/img/delete_icon.png' alt='Xóa tin tức' style='width: 50px; height: auto; margin-left: 20px; margin-top: 10px;'></button>"; 
            echo "</form>";
            echo "</span>";
            echo "</div>";
            echo "</div>";
            echo "</a>";
            echo "</div>";
        }
    }
    $connect->close();
    ?>
</div>    
    <div class="col-12">
    <ul class="pagination d-flex justify-content-center mt-5">
        
    </ul>
    </div>
<script>
    let thisPage = 1; let limit = 5;
    let list = document.querySelectorAll('.tintuc');
    function loadItem(){
        let bigin=limit *(thisPage-1);
        let end=limit *thisPage-1;
        list.forEach((item,key)=>{
            if(key >=bigin && key<=end){
                item.style.display='block';
            }else{
                item.style.display='none';
            }
        })
        listPage();
    }
    loadItem();
    function listPage(){
        let countt =Math.ceil(list.length / limit);
        document.querySelector('.pagination').innerHTML ='';
        //pagination:là class của thẻ ul

        if(thisPage !=1){
            let quayToi=document.createElement('li');
            quayToi.innerText="<";
            quayToi.setAttribute('onclick',"changePage("+(thisPage-1)+")");
            document.querySelector('.pagination').appendChild(quayToi);
        }

        for(i=1;i<=countt;i++){
            let newPage=document.createElement('li');
            newPage.innerText=i;
            if(i == thisPage){
                newPage.classList.add('activee');
            }
            newPage.setAttribute('onclick',"changePage("+i+")");
            document.querySelector('.pagination').appendChild(newPage);
        }

        if(thisPage !=countt){
            let quayVe=document.createElement('li');
            quayVe.innerText=">";
            quayVe.setAttribute('onclick',"changePage("+(thisPage+1)+")");
            document.querySelector('.pagination').appendChild(quayVe);}
    }
    function changePage(i){
        thisPage =i;
        loadItem();
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

<?php include 'includes/footer.php'; ?>