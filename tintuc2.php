
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/tintuc.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="assets/css/loadtintuc.css?v=<?php echo time(); ?>">

    <?php include 'tintuc.php'; ?>
    
<div>
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