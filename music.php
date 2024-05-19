<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Audio Player</title>
  <link rel="stylesheet" href="css/music.css">
</head>
<body onclick="playAudio()">

<?php
// Khai báo biến
$audioFile = "img/A Transparent Moon (Liuli Pavilion).mp3";
$audioIcon = "img/speaker.png";
$speakerOnIcon = "img/speaker.png";
$speakerOffIcon = "img/speaker-off.png";
?>

<audio id="myAudio" src="<?php echo $audioFile; ?>" autoplay ontimeupdate="checkAudio()"></audio>
<div class="audio-controls">
  <button id="audioToggleButton">
    <img id="audioIcon" src="<?php echo $audioIcon; ?>" alt="Speaker">
  </button>
</div>

<script>
function checkAudio() {
  var audio = document.getElementById("myAudio");
  if (audio.currentTime > 0) {
    audio.play();
    audio.removeEventListener("timeupdate", checkAudio);
  }
}
</script>

</body>
</html>
