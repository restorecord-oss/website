<?php
if(isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] === '::1' ||  $_SERVER['REMOTE_ADDR'] === 'localhost' ||  $_SERVER['REMOTE_ADDR'] === '127.0.0.1')) {
    $link = mysqli_connect('localhost', 'root', '', 'rest_main');
} else {
    $link = mysqli_connect("localhost", "restorecord_db", "oNeFHuxBMt6h6z", "restorecord_main");
}

$token = "NzkxMTA2MDE4MTc1NjE0OTg4.X-KU5A.5JLKR-T1tfcmu5hSFbj2Ol9z5aE";
$secret = "";
$client_id = "";
$sellix_secret = "xbre1dpAV16XwexsitVmjQOqtF3X53gz";
$sellix_api = "XTcdy2sW1P8hwI6f4aOVBZQ7g4QG7t7zoJGIGrY2wyAvkCOn1los2tm8nubfB5Si";