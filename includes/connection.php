<?php
$link = NULL;

if(isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] === '::1' ||  $_SERVER['REMOTE_ADDR'] === 'localhost' ||  $_SERVER['REMOTE_ADDR'] === '127.0.0.1')) {
    $link = mysqli_connect('localhost:3306', 'root', '', 'rest_main') or die ("Error contact staff");
} else {
    $link = mysqli_connect("localhost", "restorecord_db", "4%gEQJMg2Qy@@2", "restorecord_main") or die ("Error contact staff");
}

//$token = "OTcxMDg2MTYzMDYwMzM0NjM0.YnFYdw.bdhFx3gL0vJEovO3T_8Lt1RhQ1M";
//$secret = "_Qzv4UPrJ9H0dhWwsulpHscl0NWIhDdB";
//$client_id = "971086163060334634";

$token = "NzkxMTA2MDE4MTc1NjE0OTg4.X-KU5A.5JLKR-T1tfcmu5hSFbj2Ol9z5aE";
$secret = "zQV10oh4g_eFsQ9AfVrxE9BuWmLdCUig";
$client_id = "791106018175614988";

$sellix_secret = "xbre1dpAV16XwexsitVmjQOqtF3X53gz";
$sellix_api = "XTcdy2sW1P8hwI6f4aOVBZQ7g4QG7t7zoJGIGrY2wyAvkCOn1los2tm8nubfB5Si";
