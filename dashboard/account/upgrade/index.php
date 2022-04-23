<?php

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
    session_unset();
    exit();
}

$role = $row['role'];
$_SESSION['role'] = $role;

$darkmode = $row['darkmode'];
$isadmin = $row['admin'];
?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Upgrade</title>

    <link rel="manifest" href="/manifest.json"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-192x192.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-256x256.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-384x384.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-512x512.png"/>


    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar" content="#4338ca"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="#4338ca">
    <meta name="apple-mobile-web-app-title" content="RestoreCord">
    <meta name="msapplication-TileImage" content="https://i.imgur.com/Nfy4OoG.png">
    <meta name="msapplication-TileColor" content="#4338ca">
    <meta name="theme-color" content="#4338ca"/>
    <meta property="og:title" content="RestoreCord"/>
    <meta property="og:description" content="RestoreCord is a verified Discord bot designed to backup your Discord Server members, roles, channels, roles & emojis"/>
    <meta property="og:url" content="https://restorecord.com"/>
    <meta property="og:image" content="https://i.imgur.com/Nfy4OoG.png"/>
    <link rel="icon" type="image/png" sizes="676x676" href="https://i.imgur.com/Nfy4OoG.png">
    <script src="https://cdn.sellix.io/static/js/embed.js"></script>
    <!-- Favicon icon -->
    <script src="https://cdn.restorecord.com/dashboard/assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Custom CSS -->
    <link href="https://cdn.restorecord.com/dashboard/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css" rel="stylesheet">
    <link href="https://cdn.restorecord.com/dashboard/assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.restorecord.com/dashboard/assets/extra-libs/c3/c3.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="https://cdn.restorecord.com/dashboard/dist/css/style.min.css" rel="stylesheet">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body data-theme="<?php if ($darkmode == 0) {
    echo "dark";
} else {
    echo "light";
} ?>">
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
                                    src="https://i.imgur.com/w65Dpnw.png" alt="user" class="rounded-circle"
                                    width="31">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                            <span class="with-arrow">
                                <span class="bg-primary"></span>
                            </span>
                            <div class="d-flex no-block align-items-center p-15 bg-primary text-white mb-2">
                                <div class="">
                                    <img src="https://i.imgur.com/w65Dpnw.png" alt="user"
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
                    <h4 class="page-title">Upgrade</h4>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid" id="content">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <!-- File export -->
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4 class="card-title">Free</h4>
                                <p>100 Member Capacity</p>
                                <p>1 Server</p>
                                <p>No Server Backups</p>
                                <p>No IP Bans</p>
                                <p>No Anti VPN/Proxy</p>
                                <p>No Verification Logs</p>
                                <p>No Auto Kick</p>
                                <p>No User Role Backup</p>
                                <p>No Customization</p>
                                <p>No API Access</p>
                                <br>
                                <button class="btn btn-lg btn-block font-medium btn-outline-success block-card disabled" disabled>Purchase</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4 class="card-title">Premium <?php if (!empty($role) && $role === 'premium') {
                                        echo '<span class="badge badge-success">Active</span>';
                                    } ?></h4>
                                <p>Unlimited Member Capacity</p>
                                <p>5 Servers</p>
                                <p>Server Backups</p>
                                <p>IP Bans</p>
                                <p>Anti VPN/Proxy</p>
                                <p>Verification Logs</p>
                                <p>Auto Kick</p>
                                <p>No User Role Backup</p>
                                <p>No Customization</p>
                                <p>No API Access</p>
                                <?php
                                if (!empty($_SESSION['role']) && $_SESSION['role'] != 'business') {
                                    echo '<br><button data-sellix-product="62485222b86a8" data-sellix-custom-username="' . $_SESSION['username'] . '" class="btn btn-lg btn-block font-medium btn-outline-warning block-sidenav">Purchase</button>';
                                } else {
                                    echo '<br><button class="btn btn-lg btn-block font-medium btn-outline-warning block-card disabled" disabled>Purchase</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <h4 class="card-title">Business <?php if (!empty($role) && $role === 'business') {
                                        echo '<span class="badge badge-success">Active</span>';
                                    } ?></h4>
                                <p>Unlimited Member Capacity</p>
                                <p>Unlimited Servers</p>
                                <p>Server Backups</p>
                                <p>IP Bans</p>
                                <p>Anti VPN/Proxy</p>
                                <p>Verification Logs</p>
                                <p>Auto Kick</p>
                                <p>User Role Backup</p>
                                <p>Customization</p>
                                <p>API Access</p>
                                <?php
                                if (!empty($_SESSION['role'])) {
                                    echo '<br><button data-sellix-product="62483a36d6bec" data-sellix-custom-username="' . $_SESSION['username'] . '" class="btn btn-lg btn-block font-medium btn-outline-info block-sidenav">Purchase</button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
<script
        src="https://cdn.restorecord.com/dashboard/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js">
</script>
<!--chartjs -->
<script src="https://cdn.restorecord.com/dashboard/dist/js/pages/dashboards/dashboard1.js"></script>
</script>
<!-- start - This is for export functionality only -->
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>
<script src="https://cdn.restorecord.com/dashboard/dist/js/pages/datatable/datatable-advanced.init.js"></script>
</body>

</html>