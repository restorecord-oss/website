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


function update()
{
    global $link;

    $guildid = sanitize($_POST['serv']);
    $servname = sanitize($_SESSION['server_to_manage']);

    $roleid = sanitize($_POST['rol']);
    $serverico = sanitize($_POST['ico']);

    $redirect = sanitize($_POST['redirect']);
    $wh = sanitize($_POST['wh']);

    $vpncheck = sanitize($_POST['vpncheck']);
    $autokick = sanitize($_POST['autokick']);
    $autokick_time = sanitize($_POST['autokick_time']);
    $bg_img = sanitize($_POST['bg_img']);
    $autoJoin = sanitize($_POST['autoJoin']);
    $redirectTime = sanitize($_POST['redirectTime']);
    $verifyDescription = sanitize($_POST['verifyDescription']);


    if (is_null($redirectTime) || is_null($autokick_time) || is_null($autokick) || is_null($autoJoin)) {
        $redirectTime = 0;
        $autokick_time = 0;
        $autokick = 0;
        $autoJoin = 0;
    }

    $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `guildid` = '$guildid' AND `name` != '$servname'"); // select all apps where owner is current user
    if (mysqli_num_rows($result) > 0) // if the user already owns an app, proceed to change app or load only app
    {
        box("Another Server Already Has This Server ID!", 3);
        return;
    }

    (mysqli_query($link, "UPDATE `servers` SET 
                     `guildid` = '$guildid', 
                     `roleid` = '$roleid', 
                     `pic` = '$serverico', 
                     `redirecturl` = NULLIF('$redirect', NULL), 
                     `webhook` = NULLIF('$wh', NULL), 
                     `vpncheck` = NULLIF('$vpncheck', 0),
                     `autoKickUnVerified` = '$autokick',
                     `autoKickUnVerifiedTime` = '$autokick_time',
                     `bg_image` = NULLIF('$bg_img', NULL),
                     `autoJoin` = '$autoJoin',
                     `redirectTime` = '$redirectTime',
                     `verifyDescription` = NULLIF('$verifyDescription', NULL)
                      WHERE `name` = '$servname' AND `owner` = '" . $_SESSION['username'] . "'") or die(mysqli_error($link)));
    mysqli_query($link, "UPDATE `members` SET `server` = '$guildid' WHERE `server` = '" . $_SESSION['serverid'] . "'");
    mysqli_query($link, "UPDATE `blacklist` SET `server` = '$guildid' WHERE `server` = '" . $_SESSION['serverid'] . "'");


    $json_data = json_encode(["content" => "" . $_SESSION['username'] . " has changed Server `" . $_SESSION['serverid'] . "` ID to `$guildid`", "username" => "RestoreCord Logs",], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init("https://discord.com/api/webhooks/955952915296694312/plldkjchPN8MEq6Xu-CV4u2T7lYm8Mcg46Cn0hLQhqvHu9qWKeJsOf6VvDDK1tw8Rgya");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
    // webhook end

    $_SESSION['serverid'] = $guildid;


    box("Updated Settings!", 2);
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
    ($result = mysqli_query($link, "SELECT * FROM `servers` WHERE `name` = '$selectOption' AND `owner` = '$username'")) or die(mysqli_error($link));
    if (mysqli_num_rows($result) === 0) {
        mysqli_close($link);
        box("You don\'t own server!", 1);
        return;
    }
    $row = mysqli_fetch_array($result);
    $banned = $row["banned"];
    if (!is_null($banned)) {
        box("This server has been banned for: " . sanitize($banned), 3);
        return;
    }

    $_SESSION['server_to_manage'] = $row['name'];
    $_SESSION['serverid'] = $row["guildid"];

    box("You have changed Server!", 2);
}

?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RestoreCord - Settings</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="300x250" href="https://i.imgur.com/Nfy4OoG.png">
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

    if (!isset($_SESSION['server_to_manage'])) {

        $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '$username' AND `banned` IS NULL"); // select all apps where owner is current user
        if (mysqli_num_rows($result) > 0) // if the user already owns an app, proceed to change app or load only app
        {
            if (mysqli_num_rows($result) === 1) // if the user only owns one app, load that app (they can still change app after it's loaded)
            {
                $row = mysqli_fetch_array($result);
                $_SESSION['server_to_manage'] = $row["name"];
                $_SESSION['serverid'] = $row["guildid"];

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

<body data-theme="<?php echo(($darkmode ? 1 : 0) ? 'light' : 'dark'); ?>">
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
                <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <a class="navbar-brand">
                    <!-- Logo icon -->
                    <b class="logo-icon">
                        <img src="https://i.imgur.com/Nfy4OoG.png" width="48px" height="48px"
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
                   aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
            </div>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin1">
                <!-- ============================================================== -->
                <!-- toggle and nav items -->
                <!-- ============================================================== -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item d-none d-md-block"><a
                                class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
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
                           href="/discord/" target="discord"> <i
                                    class="mdi mdi-discord font-24"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle waves-effect waves-dark"
                           href="/telegram/" target="telegram"> <i
                                    class="mdi mdi-telegram font-24"></i>
                        </a>
                    </li>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="https://i.imgur.com/w65Dpnw.png" alt="user" class="rounded-circle"
                                    width="31"></a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <span class="with-arrow"><span class="bg-primary"></span></span>
                            <div class="d-flex no-block align-items-center p-15 bg-primary text-white mb-2">
                                <div class=""><img src="https://i.imgur.com/w65Dpnw.png" alt="user"
                                                   class="img-circle" width="60"></div>
                                <div class="ml-2">
                                    <h4 class="mb-0"><?php echo $_SESSION['username']; ?></h4>
                                    <p class=" mb-0"><?php echo $_SESSION['email']; ?></p>
                                </div>
                            </div>
                            <a class="dropdown-item" href="../../account/settings/"><i
                                        class="ti-settings mr-1 ml-1"></i> Account Settings</a>
                            <a class="dropdown-item" href="../../account/logout/"><i
                                        class="fa fa-power-off mr-1 ml-1"></i> Logout</a>
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
                    <h4 class="page-title">Settings</h4>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->

        <div class="main-panel" id="createapp" style="padding-left:30px;display:none;">
            <!-- Page Heading -->
            <br>
            <h1 class="h3 mb-2 text-gray-800">Create A Server</h1>
            <br>
            <br>
            <form method="POST" action="">
                <input type="text" id="appname" name="appname" class="form-control"
                       placeholder="Server Name...">
                <br>
                <br>
                <button type="submit" name="ccreateapp" class="btn btn-primary" style="color:white;">Submit</button>
            </form>
        </div>


        <div class="main-panel" id="changeapp" style="padding-left:30px;display:none;">
            <!-- Page Heading -->
            <br>
            <h1 class="h3 mb-2 text-gray-800">Choose A Server</h1>
            <br>
            <br>
            <form class="text-left" method="POST" action="">
                <select class="form-control" name="taskOption">
                    <?php
                    $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '$username'");

                    $rows = array();
                    while ($r = mysqli_fetch_assoc($result)) {
                        $rows[] = $r;
                    }

                    foreach ($rows as $row) {

                        $appname = $row['name'];
                        ?>
                        <option><?php echo $appname; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <br>
                <br>
                <button type="submit" name="change" class="btn btn-primary" style="color:white;">Submit</button>
                <a style="padding-left:5px;color:#4e73df;" id="createe">Create Server</a>
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

        <?php if (isset($_SESSION['server_to_manage'])) { ?>
        <div class="main-panel" id="renameapp" style="padding-left:30px;display:none;">
            <!-- Page Heading -->
            <br>
            <h1 class="h3 mb-2 text-gray-800">Rename</h1>
            <br>
            <br>
            <div class="col-12">
                <div class="card-body">
                    <form class="form" method="POST" action="">
                        <div class="form-group row">
                            <label for="example-tel-input" class="col-2 col-form-label">Selected App</label>
                            <div class="col-10">
                                <input class="form-control"
                                       value="<?php echo htmlspecialchars($_SESSION['server_to_manage']); ?>"
                                       placeholder="Old Server Name" required disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-tel-input" class="col-2 col-form-label">New Name</label>
                            <div class="col-10">
                                <input class="form-control" name="name" type="text" placeholder="New Name" required>
                            </div>
                        </div>
                        <br>
                        <br>
                        <button type="submit" name="renameserver" class="btn btn-primary" style="color:white;">Submit
                        </button>
                        <a style="padding-left:5px;color:#4e73df;" id="cancel">Cancel</a>
                    </form>
                </div>
            </div>
            <?php } ?>
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

        <!-- ============================================================== -->
        <div class="container-fluid" id="content" style="display:none">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- File export -->
            <div class="row">
                <div class="col-12">
                    <?php heador(); ?>
                    <br>
                    <a href="JavaScript:newPopup('https://discord.com/oauth2/authorize?client_id=791106018175614988&permissions=268435457&scope=applications.commands%20bot');"
                       class="btn btn-info"> <i class="fab fa-discord"></i> Add Bot</a>
                    <br><br>
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
                    if (isset($_SESSION['server_to_manage'])) {
                        $servname = sanitize($_SESSION['server_to_manage']);
                        ($result = mysqli_query($link, "SELECT * FROM `servers` WHERE `name` = '$servname' AND `owner` = '$username'")) or die(mysqli_error($link));
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $serv = $row['guildid'];
                                $rol = $row['roleid'];
                                $ico = $row['pic'];
                                $redirect = $row['redirecturl'];
                                $vpncheck = $row['vpncheck'];
                                $auto_kick = $row['autoKickUnVerified'];
                                $auto_kick_timer = $row['autoKickUnVerifiedTime'];
                                $bg_img = $row['bg_image'];
                                $autoJoin = $row['autoJoin'];
                                $redirectTime = $row['redirectTime'];
                                $verifyDescription = $row['verifyDescription'];
                                $wh = $row['webhook'];
                            }
                        }
                    }
                    ?>

                    <?php
                    if (isset($_SESSION['server_to_manage'])) {
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <form class="form" method="post">
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">Server ID</label>
                                    <div class="col-10">
                                        <input class="form-control" maxlength="18" name="serv" type="number"
                                               value="<?php echo $serv; ?>" placeholder="Guild/Server ID" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">Role ID</label>
                                    <div class="col-10">
                                        <input class="form-control" maxlength="18" name="rol"
                                               value="<?php echo $rol; ?>" type="number"
                                               placeholder="Role of verified role" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">Icon</label>
                                    <div class="col-10">

                                        <input class="form-control" name="ico" value="<?php echo $ico; ?>"
                                               type="text" placeholder="URL to image for icon">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">Redirect
                                        Link</label>
                                    <div class="col-10">
                                        <input class="form-control" name="redirect" value="<?php echo $redirect; ?>"
                                               type="url"
                                               placeholder="Link to redirect to after your members verify">
                                    </div>
                                    <?php
                                    if ($role != "free") {
                                        ?>
                                        <label for="example-tel-input" class="col-2 col-form-label">Redirect Time
                                            (sec)</label>
                                        <div class="col-10">
                                            <input class="form-control" name="redirectTime"
                                                   placeholder="Redirect after ... seconds after verifying"
                                                   type="number" value="<?= $redirectTime ?>">
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">Webhook Link</label>
                                    <div class="col-10">
                                        <?php
                                        if ($role == "free") {
                                            ?>
                                            <input class="form-control" placeholder="Premium only feature" disabled>
                                            <input type="hidden" name="wh">
                                            <?php
                                        } else {
                                            ?>
                                            <input class="form-control" name="wh" value="<?php echo $wh; ?>"
                                                   type="url"
                                                   placeholder="Discord webhook link for verification logs">
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">Auto Join Server (on
                                        verify)</label>
                                    <div class="col-10">
                                        <select name="autoJoin" class="form-control">
                                            <option value="1" <?= $autoJoin == 1 ? ' selected="selected"' : '' ?>>
                                                Enabled
                                            </option>
                                            <option value="0" <?= $autoJoin == 0 ? ' selected="selected"' : '' ?>>
                                                Disabled
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">VPN Check</label>
                                    <div class="col-10">
                                        <?php
                                        if ($role == "free") {
                                            ?>
                                            <input class="form-control" placeholder="Premium only feature" disabled>
                                            <input type="hidden" value="0" name="vpncheck">
                                            <?php
                                        } else {
                                            ?>
                                            <select name="vpncheck" class="form-control">
                                                <option value="1" <?= $vpncheck == 1 ? ' selected="selected"' : '' ?>>
                                                    Enabled
                                                </option>
                                                <option value="0" <?= $vpncheck == 0 ? ' selected="selected"' : '' ?>>
                                                    Disabled
                                                </option>
                                            </select>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="example-tel-input" class="col-2 col-form-label">Auto kick</label>
                                    <div class="col-10">
                                        <?php
                                        if ($role == "free") {
                                        ?>
                                        <input class="form-control" placeholder="Premium only feature" disabled>
                                        <input type="hidden" value="0" name="autokick">
                                        <input type="hidden" name="autokick_time">
                                    </div>
                                    <?php
                                    } else {
                                    ?>
                                    <select name="autokick" class="form-control">
                                        <option value="1" <?= $auto_kick == 1 ? ' selected="selected"' : '' ?>>
                                            Enabled
                                        </option>
                                        <option value="0" <?= $auto_kick == 0 ? ' selected="selected"' : '' ?>>
                                            Disabled
                                        </option>
                                    </select>
                                </div>

                                <label for="example-tel-input" class="col-2 col-form-label">Auto kick Timer
                                    (minutes)</label>
                                <div class="col-10">
                                    <input name="autokick_time" class="form-control"
                                           placeholder="Kick after how many minutes" type="number"
                                           value="<?= $auto_kick_timer ?>">
                                </div>
                                <?php
                                }
                                ?>

                        </div>
                        <div class="form-group row">
                            <label for="example-tel-input" class="col-2 col-form-label">Background Image</label>
                            <div class="col-10">
                                <?php
                                if ($role == "free" || $role == "premium") {
                                    ?>
                                    <input class="form-control" placeholder="Business only feature" disabled>
                                    <input type="hidden" name="bg_img">
                                    <?php
                                } else {
                                    ?>
                                    <input class="form-control" name="bg_img" value="<?php echo $bg_img; ?>"
                                           type="url"
                                           placeholder="Verification page Background Image (url)">
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-tel-input" class="col-2 col-form-label">Verify page Description</label>
                            <div class="col-10">
                                <?php
                                if ($role == "free" || $role == "premium") {
                                    ?>
                                    <input class="form-control" placeholder="Business only feature" disabled>
                                    <input type="hidden" name="verifyDescription">
                                    <?php
                                } else {
                                    ?>
                                    <input class="form-control" name="verifyDescription"
                                           value="<?php if (!is_null($verifyDescription)) {
                                               echo $verifyDescription;
                                           } ?>"
                                           type="text"
                                           placeholder="Verification page Description (long text below)">
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <button name="updatesettings" class="btn btn-success"><i class="fa fa-check"></i>
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