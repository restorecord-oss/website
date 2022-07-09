<!DOCTYPE html>
<html dir="ltr" lang="en">

<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include '../../../includes/connection.php';
include '../../../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    echo "<meta http-equiv='Refresh' Content='0; url=../../../login/'>";
    session_destroy();
    exit();
}

$role = $row['role'];
$_SESSION['role'] = $role;

$darkmode = $row['darkmode'];
$isadmin = $row['admin'];

if (isset($_POST['change'])) {
    changeServer($username);
}

function changeServer($username)
{
    global $link;
    $selectOption = sanitize($_POST['taskOption']);
    ($result = mysqli_query($link, "SELECT * FROM `servers` WHERE `name` = '$selectOption' AND `owner` = '$username'")) or die(mysqli_error($link));
    if (mysqli_num_rows($result) === 0) {
        box("You don't own this server!", 3);
        return;
    }
    $row = mysqli_fetch_array($result);
    $banned = $row["banned"];
    if (!is_null($banned)) {
        box("This server is banned! (" . sanitize($banned) . ")", 3);
        return;
    }

    $_SESSION['server_to_manage'] = $row['name'];
    $_SESSION['serverid'] = $row["guildid"];

    box("You\'ve changed Server", 2);
    echo "<meta http-equiv='Refresh' Content='2;'>";
}

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Members</title>

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
    <meta property="og:description" content="RestoreCord is a verified Discord bot designed to backup your Discord Server members, roles, channels, roles & emojis"/>
    <meta property="og:url" content="https://restorecord.com"/>
    <meta property="og:image" content="https://cdn.restorecord.com/logo.png"/>
    <link rel="icon" type="image/png" sizes="676x676" href="https://cdn.restorecord.com/logo512.png">
    <script src="https://cdn.restorecord.com/dashboard/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link
            href="https://cdn.restorecord.com/dashboard/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css"
            rel="stylesheet">
    <link href="https://cdn.restorecord.com/dashboard/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.restorecord.com/dashboard/assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="https://cdn.restorecord.com/dashboard/dist/css/style.min.css" rel="stylesheet">


    <script src="https://cdn.restorecord.com/dashboard/unixtolocal.js"></script>


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php

    if (!isset($_SESSION['server_to_manage'])) // no app selected yet
    {

        $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '$username' AND `banned` IS NULL"); // select all apps where owner is current user
        if (mysqli_num_rows($result) > 0) // if the user already owns an app, proceed to change app or load only app
        {

            if (mysqli_num_rows($result) === 1) // if the user only owns one app, load that app (they can still change app after it's loaded)
            {
                $row = mysqli_fetch_array($result);
                $_SESSION['server_to_manage'] = $row["name"];
                $_SESSION['serverid'] = $row["guildid"]; ?>
                <script>
                    $(document).ready(function () {
                        $('#content').css("display", "block")
                    });
                </script>
            <?php
            }
            else
            {
            // otherwise if the user has more than one app, choose which app to load
            ?>
                <script>
                    $(document).ready(function () {
                        $('#changeapp').css("display", "block")
                    });
                </script>
                <?php
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
        // if user has already selected an app, load it
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
                        <img src="https://cdn.restorecord.com/logo512.png" width="48px" height="48px"
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
                    <h4 class="page-title">Members</h4>
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
                       placeholder="Server Name..."/>
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
                <a
                        style="padding-left:5px;color:#4e73df;" id="createe">Create Server
                </a>
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
                    <a href="JavaScript:newPopup('https://discord.com/oauth2/authorize?client_id=791106018175614988&permissions=8&scope=applications.commands%20bot');" class="btn btn-info">
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
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="file_export" class="table table-striped table-bordered display">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    if (isset($_SESSION['server_to_manage'])) {
                                        ($result = mysqli_query($link, "SELECT * FROM `members` WHERE `server` = '" . $_SESSION['serverid'] . "'")) or die(mysqli_error($link));

                                        $rows = array();
                                        while ($r = mysqli_fetch_assoc($result)) {
                                            $rows[] = $r;
                                        }

                                        foreach ($rows as $row) {

                                            $user = $row['userid'];
                                            ?>

                                            <tr>

                                                <td><?php echo $user; ?><?php if ($row['access_token'] === "broken") { echo '<div class="badge badge-danger">Unlinked (broken)</div>'; } ?></td>

                                                <form method="POST">
                                                    <td>
                                                        <button type="button" class="btn btn-info dropdown-toggle"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            Manage
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <button class="dropdown-item" name="deleteuser"
                                                                    value="<?php echo $user; ?>">Delete
                                                            </button>
                                                            <button class="dropdown-item" name="banuser"
                                                                    value="<?php echo $user; ?>">Ban
                                                            </button>
                                                    </td>
                                            </tr>
                                            </form>
                                            <?php

                                        }
                                    }

                                    ?>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Show / hide columns dynamically -->

            <!-- Column rendering -->

            <!-- Row grouping -->

            <!-- Multiple table control element -->

            <!-- DOM / jQuery events -->

            <!-- Complex headers with column visibility -->

            <!-- language file -->

            <?php

            if (isset($_POST['deleteuser'])) {
                $user = sanitize($_POST['deleteuser']);
                mysqli_query($link, "DELETE FROM `members` WHERE `userid` = '$user' AND `server` = '" . $_SESSION['serverid'] . "'");
                if (mysqli_affected_rows($link) !== 0) // check query impacted something, else show error
                {
                    box("User Successfully Deleted!", 2);
                    echo "<meta http-equiv='Refresh' Content='2'>";
                } else {
                    box("Failed To Delete User!", 3);
                }
            }

            if (isset($_POST['banuser'])) {
                if ($role === "free") {
                    box("Premium only feature!", 3);
                    echo "<meta http-equiv='Refresh' Content='2'>";
                    return;
                }

                $user = sanitize($_POST['banuser']);

                $result = mysqli_query($link, "SELECT `ip` FROM `members` WHERE `userid` = '$user' AND `server` = '" . $_SESSION['serverid'] . "'");
                if (mysqli_num_rows($result) === 0) {
                    box("User not Found!", 3);
                    echo "<meta http-equiv='Refresh' Content='2'>";
                    return;
                }

                $row = mysqli_fetch_array($result);
                $ip = $row["ip"];

                if (is_null($ip)) {
                    box("No IP could be found.<br>This will only ban the user from the server", 1);
                    echo "<meta http-equiv='Refresh' Content='5'>";
                    return;
                }

                mysqli_query($link, "INSERT INTO `blacklist`(`userid`,`ip`, `server`) VALUES ('$user','$ip','" . $_SESSION['serverid'] . "')");
                if (mysqli_affected_rows($link) !== 0) {
                    mysqli_query($link, "DELETE FROM `members` WHERE `userid` = '$user' AND `server` = '" . $_SESSION['serverid'] . "'");
                    box("User Successfully Banned!", 2);
                    echo "<meta http-equiv='Refresh' Content='2'>";
                } else {
                    box("Failed To Ban User!", 3);
                }
            }


            ?>

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
<!--chartis chart-->
<script src="https://cdn.restorecord.com/dashboard/assets/libs/chartist/dist/chartist.min.js"></script>
<script src="https://cdn.restorecord.com/dashboard/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
<!--chartjs -->
<script src="https://cdn.restorecord.com/dashboard/dist/js/pages/dashboards/dashboard1.js"></script>
<script src="https://cdn.restorecord.com/dashboard/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js"></script>
<!-- start - This is for export functionality only -->
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>-->
<!--<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>-->

<script src="https://cdn.restorecord.com/dashboard/dist/js/pages/datatable/datatable-advanced.init.js"></script>

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