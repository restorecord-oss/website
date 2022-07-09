<!DOCTYPE html>
<html dir="ltr" lang="en">

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../../includes/connection.php';
include '../../../includes/functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../../../login/");
    exit();
}

$username = $_SESSION['username'];

premium_check($username);
test($_SESSION['username'], $_SESSION['pverif']);


($result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username'")) or die(mysqli_error($link));
$row = mysqli_fetch_array($result);

$banned = $row['banned'];
if (!is_null($banned)) {
    session_destroy();
    session_unset();
    echo "<meta http-equiv='Refresh' Content='0; url=/login'>";
    exit();
}

$role = $row['role'];
$_SESSION['role'] = $role;

$darkmode = $row['darkmode'];
$isadmin = $row['admin'];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$token = NULL;
$clientId = NULL;
$clientSecret = NULL;


function update()
{
    global $link;
    global $username;

    global $token;
    global $clientId;
    global $clientSecret;

    $old_clientId = sanitize($_SESSION['custombot_to_manage']);

    $new_token = sanitize($_POST['token']);
    $new_clientId = sanitize($_POST['clientId']);
    $new_clientSecret = sanitize($_POST['clientSecret']);

    $result = mysqli_query($link, "SELECT * FROM `custombots` WHERE `token` = '$new_token' AND `owner` = '$username'"); // select all apps where owner is current user
    if (mysqli_num_rows($result) > 1) // if the user already owns an app, proceed to change app or load only app
    {
        box("Another Bot already has these same Details!", 3);
        return;
    }

    $curl = curl_init("https://discord.com/api/v9/users/@me");
    $headers = array("Authorization: Bot " . $new_token);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $resp = curl_exec($curl);
    curl_close($curl);
    $json = json_decode($resp, true);

    if (isset($json['message'])) {
        box("Invalid Token!", 3);
        return;
    }

    if (isset($json['id'])) {
        if ($new_clientId !== $json['id']) {
            box("Details do not match with Discord (wrong Client Id)", 3);
            return;
        }
    }


    (mysqli_query($link, "UPDATE `custombots` SET `token` = '$new_token', `clientId` = '$new_clientId', `clientSecret` = '$new_clientSecret' WHERE `clientId` = '$old_clientId' AND `owner` = '" . $_SESSION['username'] . "'") or die(mysqli_error($link)));

    $token = $new_token;
    $clientId = $new_clientId;
    $clientSecret = $new_clientSecret;

    $json_data = json_encode([
        "content" => "" . $_SESSION['username'] . " has changed Custom Bot `" . $_SESSION['custombot_to_manage'] . "` ID to `$new_clientId`",
        "username" => "RestoreCord Logs",
    ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);


    $ch = curl_init("https://discord.com/api/webhooks/971154653997842472/In7DnfIbL2lwPCD6Z7Jsq2YGvBGb9PsT5oq50e74j9xFq3JFHEwYBsRLCPYrOvibB2Ho");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    // webhook end


    box("Updated Bot!", 2);
}

if (isset($_POST['updatesettings'])) {
    try {
        update();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

if (isset($_POST['change'])) {
    changeServer($username);
}

function changeServer($username)
{
    global $link;
    $selectOption = sanitize($_POST['taskOption']);
    ($result = mysqli_query($link, "SELECT * FROM `custombots` WHERE `clientId` = '$selectOption' AND `owner` = '$username'")) or die(mysqli_error($link));
    if (mysqli_num_rows($result) === 0) {
        box("You don\'t own this Custom Bot!", 1);
        return;
    }
    $row = mysqli_fetch_array($result);

    $_SESSION['custombot_to_manage'] = $row['clientId'];
    $_SESSION['bot_token'] = $row['token'];

    box("You have changed Server!", 2);
}

if (isset($_SESSION['custombot_to_manage'])) {
    try {
        LoadBotSettings(sanitize($_SESSION['custombot_to_manage']));
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function LoadBotSettings($botId)
{
    global $link;
    global $token;
    global $clientId;
    global $clientSecret;
    global $username;

    ($result = mysqli_query($link, "SELECT * FROM `custombots` WHERE `clientId` = '$botId' AND `owner` = '$username'")) or die(mysqli_error($link));
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $token = $row['token'];
            $clientId = $row['clientId'];
            $clientSecret = $row['clientSecret'];
        }
    }
}

?>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Custom Bot</title>

    <link rel="manifest" href="/manifest.json"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-192x192.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-256x256.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-384x384.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-512x512.png"/>


    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar" content="#4338ca"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="#4338ca">
    <meta name="apple-mobile-web-app-title" content="RestoreCord">
    <meta name="msapplication-TileImage" content="https://cdn.restorecord.com/logo.png">
    <meta name="msapplication-TileColor" content="#4338ca">
    <meta name="theme-color" content="#4338ca"/>
    <meta property="og:title" content="RestoreCord"/>
    <meta property="og:description"
          content="RestoreCord is a verified Discord bot designed to backup your Discord Server members, roles, channels, roles & emojis"/>
    <meta property="og:url" content="https://restorecord.com"/>
    <meta property="og:image" content="https://cdn.restorecord.com/logo.png"/>
    <link rel="icon" type="image/png" sizes="676x676" href="https://cdn.restorecord.com/logo.png">
    <script src="https://cdn.restorecord.com/dashboard/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Custom CSS -->
    <!-- Custom CSS -->
    <link href="https://cdn.restorecord.com/dashboard/dist/css/style.min.css" rel="stylesheet">
    <script src="https://cdn.restorecord.com/dashboard/unixtolocal.js"></script>
    <link href="https://cdn.restorecord.com/dashboard/assets/extra-libs/c3/c3.min.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    <?php

    if (!isset($_SESSION['custombot_to_manage'])) {

        $result = mysqli_query($link, "SELECT * FROM `custombots` WHERE `owner` = '$username'"); // select all apps where owner is current user
        if (mysqli_num_rows($result) > 0) // if the user already owns an app, proceed to change app or load only app
        {
            if (mysqli_num_rows($result) === 1) // if the user only owns one app, load that app (they can still change app after it's loaded)
            {
                $row = mysqli_fetch_array($result);
                $_SESSION['custombot_to_manage'] = $row["clientId"];
                LoadBotSettings(sanitize($_SESSION['custombot_to_manage']));

                echo '<script>
                    $(document).ready(function () {
                        $("#content").css("display", "block")
                    });
                </script>';
            } else {
                // otherwise if the user has more than one app, choose which app to load

                echo '<script>
                    $(document).ready(function () {
                        $("#changeapp").css("display", "block")
                    });
                </script>';
            }
        } else {
            // if user doesnt have any apps created, take them to the screen to create an app

            echo '<script>
                $(document).ready(function () {
                    $("#createapp").css("display", "block")
                });
            </script>';

        }
    } else {
        // app already selected, load page like normal
        echo '<script>
            $(document).ready(function () {
                $("#content").css("display", "block")
                $("#sticky-footer bg-white").css("display", "block")
            });
        </script>';
    }
    ?>
</head>

<body data-theme="<?php echo($darkmode ? 'light' : 'dark'); ?>">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper" data-layout="vertical" data-navbarbg="skin1" data-sidebartype="full"
     data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
    <!-- ============================================================== -->
    <!-- Topbar header - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <header class="topbar" data-navbarbg="skin1">
        <nav class="navbar top-navbar navbar-expand-md navbar-dark">
            <div class="navbar-header" data-logobg="skin5">
                <!-- This is for the sidebar toggle which is visible on mobile only -->
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                    <i
                            class="ti-menu ti-close"></i>
                </a>
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <a class="navbar-brand">
                    <!-- Logo icon -->
                    <b class="logo-icon">
                        <img src="https://cdn.restorecord.com/logo.png" width="48px" height="48px"
                             class="mr-2 hidden md:inline pointer-events-none noselect">
                    </b>
                </a>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Toggle which is visible on mobile only -->
                <!-- ============================================================== -->
                <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                   data-toggle="collapse" data-target="#navbarSupportedContent"
                   aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i
                            class="ti-more"></i>
                </a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin1">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item d-none d-md-block">
                        <a
                                class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar">
                            <i class="mdi mdi-menu font-24"></i>
                        </a>
                    </li>
                </ul>
                <!-- ============================================================== -->
                <!-- Right side toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav">
                    <!-- ============================================================== -->
                    <!-- create new -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark"
                           href="/discord/" target="discord">
                            <i
                                    class="mdi mdi-discord font-24"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark"
                           href="/telegram/" target="telegram">
                            <i
                                    class="mdi mdi-telegram font-24"></i>
                        </a>
                    </li>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img
                                    src="https://cdn.restorecord.com/logo.png" alt="user" class="rounded-circle"
                                    width="31">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <span class="with-arrow">
                                <span class="bg-primary"></span>
                            </span>
                            <div class="d-flex no-block align-items-center p-15 bg-primary text-white mb-2">
                                <div class="">
                                    <img src="https://cdn.restorecord.com/logo.png" alt="user"
                                         class="img-circle" width="60">
                                </div>
                                <div class="ml-2">
                                    <h4 class="mb-0"><?php echo $_SESSION['username']; ?></h4>
                                    <p class=" mb-0"><?php echo $_SESSION['email']; ?></p>
                                </div>
                            </div>
                            <a class="dropdown-item" href="../../account/settings/">
                                <i
                                        class="ti-settings mr-1 ml-1"></i>
                                Account Settings
                            </a>
                            <a class="dropdown-item" href="../../account/logout/">
                                <i
                                        class="fa fa-power-off mr-1 ml-1"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                </ul>
            </div>
        </nav>
    </header>
    <!-- ============================================================== -->
    <!-- End Topbar header -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <aside class="left-sidebar" data-sidebarbg="skin5">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <?php sidebar($isadmin); ?>
                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>
    <!-- ============================================================== -->
    <!-- End Left Sidebar - style you can find in sidebar.scss  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-5 align-self-center">
                    <h4 class="page-title">Custom Bot</h4>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->

        <div class="main-panel" id="createapp" style="padding-left:30px;padding-right:30px;display:none;">
            <!-- Page Heading -->
            <br>
            <h1 class="h3 mb-2 text-gray-800">Create A Bot</h1>
            <br>
            <br>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group row">
                                <label for="token" class="col-2 col-form-label">Bot Token</label>
                                <div class="col-10">
                                    <input type="password" id="token" name="token" class="form-control"
                                           placeholder="Bot Token">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-tel-input" class="col-2 col-form-label">Client Secret</label>
                                <div class="col-10">
                                    <input type="text" id="clientSecret" name="clientSecret" class="form-control"
                                           placeholder="Client Secret">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-tel-input" class="col-2 col-form-label">Client Id</label>
                                <div class="col-10">
                                    <input type="text" id="clientId" name="clientId" class="form-control"
                                           placeholder="Client Id">
                                </div>
                            </div>
                            <!--                            <div class="form-group row">-->
                            <!--                                <label for="example-tel-input" class="col-2 col-form-label">Redirect Url</label>-->
                            <!--                                <div class="col-10">-->
                            <!--                                    <input type="text" id="urlRedirect" name="urlRedirect" class="form-control"-->
                            <!--                                           placeholder="Redirect Url">-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <button type="submit" name="createbot" class="btn btn-primary" style="color:white;">Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="main-panel" id="changeapp" style="padding-left:30px;padding-right:30px;display:none;">
            <!-- Page Heading -->
            <br>
            <h1 class="h3 mb-2 text-gray-800">Choose A Bot</h1>
            <br>
            <br>
            <form class="text-left" method="POST" action="">
                <select class="form-control" name="taskOption">
                    <?php
                    $result = mysqli_query($link, "SELECT * FROM `custombots` WHERE `owner` = '$username'");

                    $rows = array();
                    while ($r = mysqli_fetch_assoc($result)) {
                        $rows[] = $r;
                    }

                    foreach ($rows as $row) {
                        $botId = $row['clientId'];


                        $curl = curl_init("https://discord.com/api/v9/users/@me");
                        $headers = array("Authorization: Bot " . $row['token'],);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HEADER, false);

                        $resp = curl_exec($curl);
                        curl_close($curl);
                        $json = json_decode($resp, true );
                        if (isset($json['username'])) {
                            $displayName = "{$json['username']}#{$json['discriminator']}";
                        } else {
                            $displayName = "Invalid Details ($botId)";
                        }
                        ?>
                        <option value="<?php echo sanitize($botId); ?>"><?php echo sanitize($displayName); ?></option>
                        <?php
                    }
                    ?>
                </select>
                <br>
                <br>
                <button type="submit" name="change" class="btn btn-primary" style="color:white;">Submit</button>
                <a style="padding-left:5px;color:#4e73df;" id="createe">Create Custom Bot</a>
            </form>
            <script type="text/javascript">
                var myLink = document.getElementById('createe');

                myLink.onclick = function () {


                    $(document).ready(function () {
                        $("#changeapp").css("display", "none");
                        $("#createapp").css("display", "block");
                    });

                }
            </script>
        </div>

        <script type="text/javascript">
            var myLink = document.getElementById('createe');

            myLink.onclick = function () {


                $(document).ready(function () {
                    $("#changeapp").css("display", "none");
                    $("#createapp").css("display", "block");
                });

            }
        </script>

        <!-- ============================================================== -->
        <div class="container-fluid" id="content" style="padding-left:30px;padding-right:30px;display:none">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- File export -->
            <div class="row">
                <div class="col-12">
                    <?php heador(); ?>
                    <br>
                    <a href="JavaScript:newPopup('https://discord.com/oauth2/authorize?client_id=791106018175614988&permissions=268435457&scope=applications.commands%20bot');"
                       class="btn btn-info">
                        <i class="fab fa-discord"></i>
                        Add Bot
                    </a>
                    <br>
                    <br>
                    <script type="text/javascript">
                        var myLink = document.getElementById('mylink');

                        myLink.onclick = function () {


                            $(document).ready(function () {
                                $("#content").css("display", "none");
                                $("#changeapp").css("display", "block");
                            });

                        }
                    </script>

                    <?php
                    if (isset($_SESSION['custombot_to_manage'], $_SESSION['bot_token'])) {
                        $botId = $_SESSION['custombot_to_manage'];


                        $curl = curl_init("https://discord.com/api/v9/users/@me");
                        $headers = array("Authorization: Bot " .  $_SESSION['bot_token']);
                        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($curl, CURLOPT_HEADER, false);

                        $resp = curl_exec($curl);
                        curl_close($curl);
                        $json = json_decode($resp, true);
                        if (isset($json['username'])) {
                            echo "<h5 class=\"mb-4\" style=\"font-weight: normal;font-size: 1rem;\">Selected Bot: <span style=\"color: #fff;\">{$json['username']}#{$json['discriminator']}</span></h5>";
                        } else {
                            echo "<h5 class=\"mb-4\" style=\"font-weight: normal;font-size: 1rem;\">Selected Bot: <span style=\"color: #fff;\">Invalid Details ($botId)</span></h5>";
                        }

                        ?>
                        <div class="card">
                            <div class="card-body">
                                <form class="form" method="post">
                                    <div class="form-group row">
                                        <label for="example-tel-input" class="col-2 col-form-label">Token</label>
                                        <div class="col-10">
                                            <input class="form-control" name="token" type="password"
                                                   value="<?php echo $token; ?>" placeholder="Token" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-tel-input" class="col-2 col-form-label">Client
                                            Secret</label>
                                        <div class="col-10">
                                            <input class="form-control" name="clientSecret" type="text"
                                                   value="<?php echo $clientSecret; ?>" placeholder="Client Secret"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="example-tel-input" class="col-2 col-form-label">Client Id</label>
                                        <div class="col-10">
                                            <input class="form-control" name="clientId" type="text"
                                                   value="<?php echo $clientId; ?>" placeholder="Client Id">
                                        </div>
                                    </div>

                                    <button name="updatesettings" class="btn btn-success">
                                        <i class="fa fa-check"></i>
                                        Save
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <!-- Show / hide columns dynamically -->

            <!-- Column rendering -->

            <!-- Row grouping -->

            <!-- DOM / jQuery events -->

            <!-- Complex headers with column visibility -->

            <!-- language file -->

            <!-- Setting defaults -->

            <!-- Footer callback -->

            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Right sidebar -->
            <!-- ============================================================== -->
            <!-- .right-sidebar -->
            <!-- ============================================================== -->
            <!-- End Right sidebar -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer text-center">
            <script>document.getElementsByClassName("footer text-center")[0].innerText = "Copyright © " + new Date().getFullYear() + " RestoreCord";</script>
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->

<!-- Bootstrap tether Core JavaScript -->
<script src="https://cdn.restorecord.com/dashboard/assets/libs/popper-js/dist/umd/popper.min.js"></script>
<script src="https://cdn.restorecord.com/dashboard/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- apps -->
<script src="https://cdn.restorecord.com/dashboard/dist/js/app.min.js"></script>
<script src="https://cdn.restorecord.com/dashboard/dist/js/app.init.dark.js"></script>
<script src="https://cdn.restorecord.com/dashboard/dist/js/app-style-switcher.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script
        src="https://cdn.restorecord.com/dashboard/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js">
</script>
<script src="https://cdn.restorecord.com/dashboard/assets/extra-libs/sparkline/sparkline.js"></script>
<!--Wave Effects -->
<script src="https://cdn.restorecord.com/dashboard/dist/js/waves.js"></script>
<!--Menu sidebar -->
<script src="https://cdn.restorecord.com/dashboard/dist/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="https://cdn.restorecord.com/dashboard/dist/js/feather.min.js"></script>
<script src="https://cdn.restorecord.com/dashboard/dist/js/custom.min.js"></script>
<!--This page JavaScript -->
<script src="https://cdn.restorecord.com/dashboard/dist/js/pages/dashboards/dashboard1.js"></script>
<!-- start - This is for export functionality only -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>

<script type="text/javascript">
    // Popup window code
    function newPopup(url) {
        popupWindow = window.open(
            url, 'popUpWindow',
            'menubar=no,width=500,height=777,location=no,resizable=no,scrollbars=yes,status=no')
    }
</script>
</body>

</html>