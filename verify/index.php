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

if (!isset($_GET['guild'])) {
    global $pieces;
    global $owner;
    global $server;
    global $svr;

    $pieces = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    // check if pieces array is empty
    if (!isset($pieces[1], $pieces[2])) {
        header('Location: /');
        exit();
    }

    $owner = urldecode(sanitize($pieces[1]));
    $server = urldecode(sanitize($pieces[2]));
    $svr = urldecode($pieces[2]);

    premium_check($owner);

    $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '$owner' AND `name` = '$server'");

    if (mysqli_num_rows($result) === 0) {
        $svr = "Not Available";
        $server_image = "https://i.imgur.com/7kiO9No.png";
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
            $banned = $row['banned'];
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
} else {
    $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `guildid` = '" . $_GET['guild'] . "'");

    if (mysqli_num_rows($result) === 0) {
        $svr = "Not Available";
        $server_image = "https://i.imgur.com/7kiO9No.png";
        $status = "noserver"; // server not found
    } else {
        $status = NULL;
        while ($row = mysqli_fetch_array($result)) {
            // id	owner	name	guildid	roleid	redirecturl	pic	vpncheck	webhook	banned	autoKickUnVerified
            $owner = $row['owner'];
            $server = $row['name'];
            $svr = $row['name'];

            $guildid = $row['guildid'];
            $roleid = $row['roleid'];
            $server_image = $row['pic'];

            $redirecturl = $row['redirecturl'];
            $webhook = $row['webhook'];
            $vpncheck = $row['vpncheck'];
            $banned = $row['banned'];
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

if (session('access_token') && !isset($_GET['guild'])) {
    global $pieces;
    global $owner;
    global $server;
    global $svr;
    global $link;
    global $vpncheck;

    $user_check = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$owner'");
    $role = mysqli_fetch_array($user_check)["role"];

    $result = mysqli_query($link, "SELECT * FROM `members` WHERE `server` = '$guildid'");
    if (mysqli_num_rows($result) > 100 && $role === "free") {
        $status = "needpremium";
    } else {

        $user = apiRequest("https://discord.com/api/users/@me");

        // echo var_dump($user);

        $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $token);
        $data = array("access_token" => session('access_token'));
        $data_string = json_encode($data);

        $result = mysqli_query($link, "SELECT * FROM `blacklist` WHERE (`userid` = '" . $user->id . "' OR `ip` = '" . getIp() . "') AND `server` = '$guildid'");
        if (mysqli_num_rows($result) > 0) {
            $status = "blacklisted";
        } else {

            $ip = getIp();
            if ($vpncheck) {
                $url = "https://proxycheck.io/v2/{$ip}?key=0j7738-281108-49802e-55d520?vpn=1";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                $json = json_decode($result);
                if ($json->$ip->proxy === "yes") {
                    $status = 'vpndetect';
                    if (!is_null($webhook)) {
                        /*
                            WEBHOOK START
                        */

                        $timestamp = date("c");
                        $json_data = json_encode(["embeds" => [["title" => "Failed VPN Check", "type" => "rich", "timestamp" => $timestamp, "color" => hexdec("ff0000"), "fields" => [["name" => ":bust_in_silhouette: User:", "value" => "```" . $user->id . "```", "inline" => true], ["name" => ":earth_americas: Client IP:", "value" => "```" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "```", "inline" => true]]]]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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
                }
            }

            if ($status !== "vpndetect") {
                $_SESSION['userid'] = $user->id;

                $url = "https://discord.com/api/guilds/$guildid/members/" . $user->id;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                // echo var_dump($result);
                // echo 'HTTP code: ' . $httpcode;

                $url = "https://discord.com/api/guilds/$guildid/members/" . $user->id . "/roles/$roleid";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);

                // echo var_dump($result);
                // echo 'HTTP code: ' . $httpcode;

                mysqli_query($link, "INSERT INTO `members` (`userid`, `access_token`, `refresh_token`, `server`, `ip`) VALUES ('" . $user->id . "', '" . $_SESSION['access_token'] . "', '" . $_SESSION['refresh_token'] . "', '$guildid', '" . getIp() . "') ON DUPLICATE KEY UPDATE `access_token` = '" . $_SESSION['access_token'] . "', `refresh_token` = '" . $_SESSION['refresh_token'] . "', `ip` = '" . getIp() . "';");
                mysqli_query($link, "UPDATE `members` SET `access_token` = '" . $_SESSION['access_token'] . "', `refresh_token` = '" . $_SESSION['refresh_token'] . "', `ip` = '" . getIp() . "' WHERE `userid` = '" . $user->id . "';");
                // mysqli_query($link, "REPLACE INTO `members` (`userid`, `access_token`, `refresh_token`, `server`,`ip`) VALUES ('" . $user->id . "', '" . $_SESSION['access_token'] . "', '" . $_SESSION['refresh_token'] . "', '$guildid', '$ip')");
                $_SESSION['access_token'] = NULL;
                $_SESSION['refresh_token'] = NULL;

                if (!is_null($webhook)) {
                    /*
                        WEBHOOK START
                    */

                    $timestamp = date("c", time());

                    $datenum = ((float)$user->id / 4194304) + 1420070400000;
                    $tst = round(($datenum / 1000));
                    $dt = new DateTime("@$tst");


                    $json_data = json_encode(["embeds" => [["title" => "Successfully Verified", "type" => "rich", "timestamp" => $timestamp, "color" => hexdec("52ef52"), "fields" => [["name" => ":bust_in_silhouette: User:", "value" => "```" . $user->id . "```", "inline" => true], ["name" => ":clock1: Account Age:", "value" => "```" . get_timeago($tst) . "```[More Info](https://lookup.ven.earth/u/" . $user->id . ")", "inline" => true], ["name" => ":earth_americas: Client IP:", "value" => "```" . getIp() . "```", "inline" => true]]]]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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

                $status = "added"; // successfully verified user
            }
        }
    }
}

if (isset($_GET['guild']) && session('access_token')) {
    $guildid = $_GET['guild'];

    $svr_check = mysqli_query($link, "SELECT * FROM `servers` WHERE `id` = '$guildid'");
    $svr_ck = mysqli_fetch_array($svr_check);

    $user_check = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '" . $svr_ck['owner'] . "'");
    $role = mysqli_fetch_array($user_check)["role"];

    $result = mysqli_query($link, "SELECT * FROM `members` WHERE `server` = '$guildid'");
    if (mysqli_num_rows($result) > 100 && $role === "free") {
        $status = "needpremium";
    } else {

        $user = apiRequest("https://discord.com/api/users/@me");

        // echo var_dump($user);

        $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $token);
        $data = array("access_token" => session('access_token'));
        $data_string = json_encode($data);

        $result = mysqli_query($link, "SELECT * FROM `blacklist` WHERE (`userid` = '" . $user->id . "' OR `ip` = '" . getIp() . "') AND `server` = '$guildid'");
        if (mysqli_num_rows($result) > 0) {
            $status = "blacklisted";
        } else {

            $ip = getIp();
            if ($vpncheck) {
                $url = "https://proxycheck.io/v2/{$ip}?key=0j7738-281108-49802e-55d520?vpn=1";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                $json = json_decode($result);
                if ($json->$ip->proxy === "yes") {
                    $status = 'vpndetect';
                    if (!is_null($webhook)) {
                        /*
                            WEBHOOK START
                        */

                        $timestamp = date("c", timestamp: time());
                        $json_data = json_encode(["embeds" => [["title" => "Failed VPN Check", "type" => "rich", "timestamp" => $timestamp, "color" => hexdec("ff0000"), "fields" => [["name" => ":bust_in_silhouette: User:", "value" => "```" . $user->id . "```", "inline" => true], ["name" => ":earth_americas: Client IP:", "value" => "```" . $_SERVER["HTTP_CF_CONNECTING_IP"] . "```", "inline" => true]]]]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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
                }
            }

            if ($status !== "vpndetect") {
                $_SESSION['userid'] = $user->id;

                $url = "https://discord.com/api/guilds/$guildid/members/" . $user->id;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                // echo var_dump($result);
                // echo 'HTTP code: ' . $httpcode;

                $url = "https://discord.com/api/guilds/$guildid/members/" . $user->id . "/roles/$roleid";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                // $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                curl_close($ch);

                // echo var_dump($result);
                // echo 'HTTP code: ' . $httpcode;

                mysqli_query($link, "INSERT INTO `members` (`userid`, `access_token`, `refresh_token`, `server`, `ip`) VALUES ('" . $user->id . "', '" . $_SESSION['access_token'] . "', '" . $_SESSION['refresh_token'] . "', '$guildid', '" . getIp() . "') ON DUPLICATE KEY UPDATE `access_token` = '" . $_SESSION['access_token'] . "', `refresh_token` = '" . $_SESSION['refresh_token'] . "', `ip` = '" . getIp() . "';");
                mysqli_query($link, "UPDATE `members` SET `access_token` = '" . $_SESSION['access_token'] . "', `refresh_token` = '" . $_SESSION['refresh_token'] . "', `ip` = '" . getIp() . "' WHERE `userid` = '" . $user->id . "';");
                // mysqli_query($link, "REPLACE INTO `members` (`userid`, `access_token`, `refresh_token`, `server`,`ip`) VALUES ('" . $user->id . "', '" . $_SESSION['access_token'] . "', '" . $_SESSION['refresh_token'] . "', '$guildid', '$ip')");
                $_SESSION['access_token'] = NULL;
                $_SESSION['refresh_token'] = NULL;

                if (!is_null($webhook)) {
                    /*
                        WEBHOOK START
                    */

                    $timestamp = date("c", time());

                    $datenum = ((float)$user->id / 4194304) + 1420070400000;
                    $tst = round(($datenum / 1000));
                    $dt = new DateTime("@$tst");


                    $json_data = json_encode(["embeds" => [["title" => "Successfully Verified", "type" => "rich", "timestamp" => $timestamp, "color" => hexdec("52ef52"), "fields" => [["name" => ":bust_in_silhouette: User:", "value" => "```" . $user->id . "```", "inline" => true], ["name" => ":clock1: Account Age:", "value" => "```" . get_timeago($tst) . "```[More Info](https://lookup.ven.earth/u/" . $user->id . ")", "inline" => true], ["name" => ":earth_americas: Client IP:", "value" => "```" . getIp() . "```", "inline" => true]]]]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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

                $status = "added"; // successfully verified user
            }
        }
    }
}

if (isset($_POST['optout'])) {
    if (session('userid')) {
        mysqli_query($link, "DELETE FROM `members` WHERE `userid` = '" . session('userid') . "' AND `server`  = '$guildid'");
        if (mysqli_affected_rows($link) != 0) {
            $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $token);

            $url = "https://discord.com/api/guilds/$guildid/members/" . session('userid') . "/roles/$roleid";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            echo $result;

            $status = "optedout";
            if (!is_null($webhook)) {
                /*
                    WEBHOOK START
                */

                $timestamp = date("c", time());

                $json_data = json_encode(["embeds" => [["title" => "User Opted Out", "type" => "rich", "timestamp" => $timestamp, "color" => hexdec("ff0000"), "fields" => [["name" => ":bust_in_silhouette: User:", "value" => "```" . session('userid') . "```", "inline" => true], ["name" => ":earth_americas: Client IP:", "value" => "```" . getIp() . "```", "inline" => true]]]]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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
    <meta property="og:description"
          content="Verify in <?php echo $svr ?>, So you're added back if this one gets deleted.">
    <meta property="og:image" content="<?php echo $server_image ?>">
    <meta name="twitter:card" content="summary_large_image">
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
            background: url(https://i.imgur.com/rYPnovh.png) repeat;
            filter: blur(1rem);
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
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
            background: #CE6161;
            box-shadow: #CE6161 0 0 5px;
            border: none;
        }

        a.button[value="yes"] {
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
            background: <?php echo $dominant_color; ?>;
            margin: auto;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .card-img:hover {
            transform: scale(1.1);
            transition: .5s;
        }

        .card {
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
            margin: 50px;
        }

        @media (max-width: 960px) {
            .bg-img {
                display: none;
            }

            .card p {
                margin-left: 20px;
                margin-right: 20px;
                font-size: 16px;
            }

            .row a:first-child {
                margin-bottom: 5px;
            }

            .row a:last-child {
                margin-top: 5px;
            }
        }

        .no-margin {
            margin: 0 !important;
        }

        .info {
            background-color: #1D1E23;
            margin-left: 20px;
            margin-right: 20px;
            padding: 25px;
            border-radius: 15px;
        }

        .value {
            background-color: #1D1E23;
            border-radius: 5px;
            padding: 5px;
            color: white;
            text-decoration: none;
        }

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
    }
    ?>
    <img class="card-img" src="<?php echo $server_image; ?>" alt="server">
    <h2><?php echo $svr; ?></h2>
    <p>Click Verify to be joined to server if it is ever raided or deleted. Click opt out to stop joining the server</p>
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
               href="https://discord.com/api/oauth2/authorize?client_id=791106018175614988&redirect_uri=https://restorecord.com/auth/&response_type=code&scope=identify+guilds.join">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check"
                     viewBox="0 0 16 16" style="transform: scale(2.5);margin-right: 10px;">
                    <path
                            d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z">
                    </path>
                </svg>
                Verify</a>
            <button class="button" value="no" name="optout" style="cursor: pointer" type="submit">
                <svg style="transform: scale(2.5);margin-right: 10px;" xmlns="http://www.w3.org/2000/svg" width="16"
                     height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                    <path
                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
                Opt out</button>
        </div>
    </form>
</div>
</body>

</html>

<!--
<!DOCTYPE html>
<html>

<head>
	<title>Verify in <?php echo $svr; ?></title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
		integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="icon" type="image/png" sizes="16x16" href="https://i.imgur.com/w65Dpnw.png">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<link id="mystylesheet" rel="stylesheet" type="text/css" href="/verify/style.css">

	<meta name="og:image" content="<?php echo $server_image; ?>">
	<meta name="description"
		content="Verify in <?php echo $svr; ?> so you're added back to server if it gets raided or deleted . ">

</head>

<body>

	<div id="box">
		<?php switch ($status) {
    case 'added':
        ?>
		<div class="alert alert - success">
			<strong>Success!</strong> Successfully verified.
		</div>
		<?php
        if (!is_null($redirecturl)) {
            echo " < meta http - equiv = 'Refresh' Content = '3;url={
$redirecturl
}' > ";
        }
        break;
    case 'optedout':
        ?>
		<div class="alert alert - success">
			<strong>Success!</strong> Successfully opted out from this server.
		</div>
		<?php
        break;
    case 'noserver':
        ?>
		<div class="alert alert - danger">
			<strong>Oh snap!</strong> No server found.
		</div>
		<?php
        break;
    case 'blacklisted':
        ?>
		<div class="alert alert - danger">
			<strong>Oh snap!</strong> This user is blacklisted.
		</div>
		<?php
        break;
    case 'banned':
        ?>
		<div class="alert alert - danger">
			<strong>Oh snap!</strong> This server has been banned for: <?php echo sanitize($banned); ?>
		</div>
		<?php
        break;
    case 'vpndetect':
        ?>
		<div class="alert alert - danger">
			<strong>Oh snap!</strong> Server owner has disabled VPN access, try again without VPN.
		</div>
		<?php
        break;
    case 'needpremium':
        ?>
		<div class="alert alert - danger">
			<strong>Oh snap!</strong> Server owner needs to purchase premium, he has reached 100 member limit for free
			users. Please tell him, thank you.
		</div>
		<?php
        break;
    case 'notauthed':
        ?>
		<div class="alert alert - danger">
			<strong>Oh snap!</strong> You need to login with discord first.
		</div>
		<?php
        break;
    case 'neveroptedin':
        ?>
		<div class="alert alert - danger">
			<strong>Oh snap!</strong> You were never opted-in.
		</div>
		<?php
        break;
    default:
        break;
}
?>
		<img id="server_pic" src=" <?php echo $server_image; ?>">
    <h2><?php echo htmlspecialchars(($svr)); ?></h2>
    <p>Click login with Discord to be joined to server if it is ever raided or deleted. Click opt out to stop
        getting joined to server.</p>
    <hr>
    <form method="post">
        <a class="btn btn-light"
           href="https://discord.com/api/oauth2/authorize?client_id=791106018175614988&redirect_uri=https%3A%2F%2Frestorecord.com%2Fauth%2F&response_type=code&scope=identify+guilds.join">Login
            With Discord</a>
        <button name="optout" class="btn btn-danger">Opt Out</button>
    </form>
</div>
</body>

</html>-->
