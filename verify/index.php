<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../includes/connection.php';
include '../includes/functions.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$pieces = NULL;
$owner = NULL;
$server = NULL;
$svr = NULL;
$guildid = NULL;

if (!isset($_GET['guild'])) {
    global $pieces;
    global $owner;
    global $server;
    global $svr;

    $pieces = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

    if (!isset($pieces[1], $pieces[2])) {
        header('Location: /');
        exit();
    }

    $owner = urldecode(sanitize($pieces[1]));
    $server = urldecode(sanitize($pieces[2]));
    $svr = urldecode($pieces[2]);

    premium_check($owner);

    $result = mysqli_query($link, "SELECT custombot,guildid,roleid,pic,redirecturl,webhook,vpncheck,redirectTime,autoKickUnVerified,autoKickUnVerifiedTime,autoJoin,bg_image,verifyDescription,banned FROM `servers` WHERE `owner` = '$owner' AND `name` = '$server'");

    if (mysqli_num_rows($result) === 0) {
        $svr = "Not Available";
        $server_image = "https://cdn.restorecord.com/logo.png";
        $status = "noserver";
    } else {
        $status = NULL;
        while ($row = mysqli_fetch_array($result)) {
            $guildid = $row['guildid'];
            $roleid = $row['roleid'];
            $server_image = $row['pic'];

            $redirecturl = $row['redirecturl'];
            $webhook = $row['webhook'];
            $vpncheck = $row['vpncheck'];
            $redirectTime = $row['redirectTime'];
            $auto_kick = $row['autoKickUnVerified'];
            $auto_kick_time = $row['autoKickUnVerifiedTime'];
            $autoJoin = $row['autoJoin'];
            $bg_img = $row['bg_image'];
            $verifyDescription = $row['verifyDescription'];
            $banned = $row['banned'];
            $token = mysqli_fetch_array(mysqli_query($link, "SELECT token FROM `custombots` WHERE `clientId` = '{$row['custombot']}'"))['token'];
        }

        if (!is_null($banned)) {
            $_SESSION['access_token'] = NULL;
            $status = "banned";
        } else {
            $_SESSION['server'] = $guildid;
            $_SESSION['owner'] = $owner;
            $_SESSION['name'] = $server;
        }

    }
}
//else {
//    $result = mysqli_query($link, "SELECT custombot,owner,name,guildid,roleid,pic,redirecturl,webhook,vpncheck,redirectTime,autoKickUnVerified,autoKickUnVerifiedTime,autoJoin,bg_image,verifyDescription,banned FROM `servers` WHERE `guildid` = '" . $_GET['guild'] . "'");
//
//    if (mysqli_num_rows($result) === 0) {
//        $svr = "Not Available";
//        $server_image = "https://i.imgur.com/7kiO9No.png";
//        $status = "noserver"; // server not found
//    } else {
//        $status = NULL;
//        while ($row = mysqli_fetch_array($result)) {
//            $owner = $row['owner'];
//            $server = $row['name'];
//            $svr = $row['name'];
//
//            $guildid = $row['guildid'];
//            $roleid = $row['roleid'];
//            $server_image = $row['pic'];
//
//            $redirecturl = $row['redirecturl'];
//            $webhook = $row['webhook'];
//            $vpncheck = $row['vpncheck'];
//            $redirectTime = $row['redirectTime'];
//            $auto_kick = $row['autoKickUnVerified'];
//            $auto_kick_time = $row['autoKickUnVerifiedTime'];
//            $autoJoin = $row['autoJoin'];
//            $bg_img = $row['bg_image'];
//            $verifyDescription = $row['verifyDescription'];
//            $banned = $row['banned'];
//            $token = mysqli_fetch_array(mysqli_query($link, "SELECT token FROM `custombots` WHERE `clientId` = '{$row['custombot']}'"))['token'];
//        }
//
//        if (!is_null($banned)) {
//            $_SESSION['access_token'] = NULL;
//            $status = "banned";
//        } else {
//            $_SESSION['server'] = $guildid;
//            $_SESSION['owner'] = $owner;
//            $_SESSION['name'] = $server;
//        }
//
//    }
//}

if (!isset($_GET['guild']) && session('access_token') && session('refresh_token')) {
    global $owner;
    global $svr;
    global $link;
    global $vpncheck;
    global $guildid;
    global $autoJoin;
    global $webhook;
    global $roleid;
    global $server_image;
    global $status;
    global $token;

    $svr_check = mysqli_query($link, "SELECT owner FROM `servers` WHERE `guildid` = '$guildid'");
    if (mysqli_num_rows($svr_check) < 1) {
        $svr = "Not Available";
        $server_image = "https://cdn.restorecord.com/logo.png";
        $status = "noserver";
        return;
    }

    $owner = mysqli_fetch_array($svr_check)['owner'];

    if (mysqli_num_rows(mysqli_query($link, "SELECT userid FROM `members` WHERE `server` = '$guildid'")) > 100 && mysqli_fetch_array(mysqli_query($link, "SELECT role FROM `users` WHERE `username` = '$owner'"))['role'] === "free") {
        $status = "needpremium";
    } else {
        $user = apiRequest("https://discord.com/api/users/@me");
        $status = PullUser($user, $guildid, $vpncheck, $webhook, $autoJoin, $roleid, $token);
    }
}
//
//if (isset($_GET['guild']) && !empty($_GET['guild']) && session('access_token') && session('refresh_token')) {
//    global $pieces;
//    global $owner;
//    global $server;
//    global $svr;
//    global $link;
//    global $vpncheck;
//    global $redirectTime;
//    global $auto_kick;
//    global $auto_kick_time;
//    global $bg_img;
//    global $verifyDescription;
//    global $autoJoin;
//    global $token;
//
//    $guildid = sanitize($_GET['guild']);
//
//    $svr_check = mysqli_query($link, "SELECT owner FROM `servers` WHERE `guildid` = '$guildid'");
//
//    if (mysqli_num_rows($svr_check) < 1) {
//        $svr = "Not Available";
//        $server_image = "https://i.imgur.com/7kiO9No.png";
//        $status = "noserver";
//        return;
//    }
//
//    $owner = mysqli_fetch_array($svr_check)['owner'];
//
//    if (mysqli_num_rows(mysqli_query($link, "SELECT userid FROM `members` WHERE `server` = '$guildid'")) > 100 && mysqli_fetch_array(mysqli_query($link, "SELECT role FROM `users` WHERE `username` = '$owner'"))['role'] === "free") {
//        $status = "needpremium";
//    } else {
//        $user = apiRequest("https://discord.com/api/users/@me");
//        $status = PullUser($user, $guildid, $vpncheck, $webhook, $autoJoin, $roleid, $token);
//    }
//}

if (isset($_POST['optout'])) {
    if (session('userid')) {

        if (isset($_GET['guild'])) {
            $guildid = $_GET['guild'];
        }

        mysqli_query($link, "DELETE FROM `members` WHERE `userid` = '" . session('userid') . "' AND `server`  = '$guildid'");
        if (mysqli_affected_rows($link) !== 0) {
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bot ' . $token,
            );

            $url = "https://discord.com/api/guilds/$guildid/members/" . session('userid') . "/roles/$roleid";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);

            $status = "optedout";
            if (!is_null($webhook)) {
                /*
                    WEBHOOK START
                */

                $timestamp = date("c");

                $json_data = json_encode([
                    "embeds" => [
                        [
                            "title" => "User Opted Out",
                            "type" => "rich",
                            "timestamp" => $timestamp,
                            "color" => hexdec("ff0000"),
                            "fields" => [
                                [
                                    "name" => ":bust_in_silhouette: User:",
                                    "value" => "```" . session('userid') . "```",
                                    "inline" => true,
                                ],
                                [
                                    "name" => ":earth_americas: Client IP:",
                                    "value" => "```" . getIp() . "```",
                                    "inline" => true,
                                ],
                            ],
                        ],
                    ],
                ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                $ch = curl_init($webhook);

                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));

                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_exec($ch);
                curl_close($ch);
                /*
                    WEBHOOK END
                */
            }
        } else {
            $status = "neveroptedin";
        }
    } else {
        $status = "notauthed";
    }
}

$dominant_color = simple_color_thief($server_image, '#1D1E23');
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter">
    <title>Verify in <?php echo $svr ?></title>
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="RestoreCord">
    <meta property="og:title" content="Verify in <?php echo $svr ?>">
    <?php
    if (!empty($verifyDescription)) {
        echo '<meta property="og:description" content="' . $verifyDescription . '">';
    } else {
        echo '<meta property="og:description" content="Verify in ' . $svr . ', So you\'re added back if this one gets deleted.">';
    }
    ?>
    <meta property="og:image" content="<?php echo $server_image ?>">
    <style>
        body {
            overflow: hidden;
            font-family: 'Inter', serif;
            background-color: #1D1E23;
            font-size: 48px;
        }

        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .button {
            color: white;
            font-weight: 500;
            text-align: center;
            margin: auto;
            background: #4a90e2;
            width: 200px;
            box-shadow: 0 5px 10px -1px rgb(0 0 0 / 32%);
            transition: .3s;
            padding: 10px;
            font-size: large;
            display: block;
            text-decoration: none;
            border-radius: 10px;
        }

        a:hover {
            color: #e9e9e9;
        }

        .bg-img {
        <?php
        if (!empty($bg_img) && str_contains($bg_img, 'http')) {
            echo "background: url('" . htmlspecialchars($bg_img) . "') repeat;";
        } else {
            echo "background: url(https://cdn.restorecord.com/logo.png) repeat;";
        }?> filter: blur(1rem);
            position: absolute;
            top: -150%;
            left: -100%;
            width: 400%;
            height: 400%;
            transform: rotate(-45deg);
            z-index: -1;
            animation: topLeftBottomRight 15s linear infinite;
        }

        @keyframes topLeftBottomRight {
            from {
                background-position: top;
            }

            to {
                background-position: bottom;
            }
        }

        button.button[value="no"] {
            transition: .5s;
            margin-left: .5rem;
            background: #CE6161;
            box-shadow: #CE6161 0 0 5px;
            border: none;
        }

        a.button[value="yes"] {
            transition: .5s;
            margin-right: .5rem;
            background: #61CE70;
            box-shadow: #61CE70 0 0 5px;
        }

        button.button[value="no"]:hover {
            background: #CE6161;
            box-shadow: #CE6161 0 0 15px;
            border: none;
        }

        a.button[value="yes"]:hover {
            background: #4dba5c;
            box-shadow: #61CE70 0 0 15px;
        }

        .card-img {
            max-width: 150px;
            border-radius: 50%;
            padding: 5px;
            transition: .5s;
            background: <?= $dominant_color ?>;
            margin: 30px auto;
        }

        .card-img:hover {
            transform: scale(1.1);
            transition: .5s;
        }

        .card {
            transition: .5s;
            background-color: #17171A;
            margin: auto;
            color: white;
            border-radius: 20px;
            /* box-shadow: 0px 5px 10px -1px rgb(0 0 0 / 32%); */
            box-shadow: #17171A 0 0 15px 5px;
            max-width: 550px;
            border: 7px solid #17171A;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .card h2 {
            text-align: center;
            font-weight: bold;
        }

        .card p {
            text-align: center;
            font-size: 19px;
            margin-left: 5px;
            margin-right: 5px;
            color: #c5c5c5;
            font-weight: 200;
        }

        .card .row {
            margin-left: 1.5rem;
            margin-right: 1.5rem;
            margin-bottom: -0.5rem;
        }

        @media (max-width: 960px) {
            button.button[value="no"] {
                transition: .5s;
                margin-left: 0.5rem;
                font-size: 1.5rem;
            }

            a.button[value="yes"] {
                transition: .5s;
                margin-right: 0.5rem;
                font-size: 1.5rem;
            }

            .bg-img {
                display: none;
            }

            .card p {
                margin-left: 40px;
                margin-right: 40px;
                font-size: 20px;
            }

            .card {
                width: 100%;
                height: 100%;
                max-width: 100%;
            }

            .card-img {
                margin: auto;
            }

            .card h2 {
                font-size: 40px;
            }

            button.button[value="report"] {
                width: 3.3rem !important;
                font-size: x-small !important;
            }
        }

        @media (max-width: 477px) {
            a.button[value="yes"] {
                margin-right: auto;
                margin-bottom: 0.25rem;
            }

            button.button[value="no"] {
                margin-left: auto;
                margin-top: 0.25rem;
            }
        }

        /*.no-margin {*/
        /*    margin: 0 !important;*/
        /*}*/

        /*.info {*/
        /*    background-color: #1D1E23;*/
        /*    margin-left: 20px;*/
        /*    margin-right: 20px;*/
        /*    padding: 25px;*/
        /*    border-radius: 15px;*/
        /*}*/

        /*.value {*/
        /*    background-color: #1D1E23;*/
        /*    border-radius: 5px;*/
        /*    padding: 5px;*/
        /*    color: white;*/
        /*    text-decoration: none;*/
        /*}*/

        .alert {
            background-color: rgb(17 17 17);
            font-size: 20px;
            color: #c5c5c5;
            border-radius: 0.75rem;
            margin: 2rem 2rem 0 2rem;
        }

        .alert-success {
            border: 2px solid #06e93d;
        }

        .alert-danger {
            border: 2px solid #c43232;
        }

        button.button[value="report"] {
            cursor: pointer;
            transition: .5s;
            margin: 0.5rem;
            background: #ce616140;
            position: absolute;
            bottom: 0;
            right: 0;
            box-shadow: #ce616140 0 0 5px;
            border: none;
            width: 5.5rem;
            font-size: medium;
        }


        button.button[value="report"]:hover {
            cursor: pointer;
            transition: .5s;
            margin: 0.5rem;
            background: #ce616160;
            position: absolute;
            bottom: 0;
            right: 0;
            box-shadow: #ce616160 0 0 5px;
            border: none;
            width: 5.5rem;
            font-size: medium;
        }
    </style>
</head>

<body>
<div class="bg-img"></div>
<div class="card">
    <?php
    global $banned;
    global $status;
    switch ($status) {
        case 'added':
            echo '<div class="alert alert-success" role="alert"><strong>Success!</strong> Successfully verified.</div>';
            break;
        case 'neveroptedin':
            echo '<div class="alert alert-success" role="alert"><strong>Success!</strong> You never opted in.</div>';
            break;
        case 'notauthed':
            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> You need to login with Discord.</div>';
            break;
        case 'vpndetect':
            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> Please disable your VPN or Proxy to verify.</div>';
            break;
        case 'blacklisted':
            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> You\'ve been blacklisted from this Server.</div>';
            break;
        case 'noserver':
            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> This server was not found.</div>';
            break;
        case 'optedout':
            echo '<div class="alert alert-success" role="alert"><strong>Success!</strong> Successfully opted out from this server.</div>';
            break;
        case 'banned':
            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> This Server has been banned (Reason: ' . sanitize($banned) . ').</div>';
            break;
        case 'needpremium':
            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> The Server Owner needs to Upgrade, he has reached 100 member limit for free users. Please tell him, thank you.</div>';
            break;
        case 'alreadyverified':
            echo '<div class="alert alert-danger" role="alert"><strong>Oh snap!</strong> You\'ve already verified.</div>';
            break;
    }

    if (!empty($redirecturl) && $status === 'added') {
        echo '<meta http-equiv="refresh" content="' . htmlspecialchars($redirectTime) . ';url=' . htmlspecialchars($redirecturl) . '">';
    }
    ?>
    <img class="card-img" src="<?= htmlspecialchars($server_image) ?>" alt="server">
    <h2><?php echo $svr; ?></h2>
    <p><?php
        if (!empty($verifyDescription)) {
            echo htmlspecialchars($verifyDescription);
        } else {
            echo 'Click Verify to be pulled into server if it is ever raided or deleted. Click opt out to stop getting pulled into the server';
        }
        ?></p>
    <hr>
    <!--<div class="info">
        <div class="no-margin row" style="justify-content: center;">
            <p style="font-size: 25px;">Active since <a class="value">Feb 22, 2022</a></p>
            <p style="font-size: 25px;"><a class="value">69</a> Linked Users</p>
        </div>
    </div>
    <hr>-->
    <form method="POST">
        <div class="row">
            <a class="button" value="yes"
               href="https://discord.com/api/oauth2/authorize?client_id=791106018175614988&redirect_uri=https://restorecord.com/auth/&response_type=code&prompt=none&scope=identify+guilds.join">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check"
                     viewBox="0 0 16 16" style="transform: scale(2.5);margin-right: 10px;">
                    <path
                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z">
                    </path>
                </svg>
                Verify
            </a>
            <button class="button" value="no" name="optout" style="cursor: pointer" type="submit">
                <svg style="transform: scale(2.5);margin-right: 10px;" xmlns="http://www.w3.org/2000/svg" width="16"
                     height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path
                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
                Opt out
            </button>
        </div>
    </form>

    <div class="text-center text-muted">
        <h6>
            <small>By verifying you agree to the
                <a href="/privacy" class="link-primary">privacy policy</a>
            </small>
        </h6>
    </div>

</div>
<!--<button class="button" value="report" type="submit">-->
<!--    Report-->
<!--</button>-->
</body>
</html>
