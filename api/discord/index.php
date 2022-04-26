<?php

include '../../includes/connection.php';
include '../../includes/functions.php';

/*
	This code is pretty much all from https://gist.github.com/Jengas/ad128715cb4f73f5cde9c467edf64b00
	It serves as the redirect page for users who 'Link Discord' in account settings
	Using their oauth2 access_token, it will first attempt to add them to the server, then give them a role if they're developer or seller
*/

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

// error_reporting(E_ALL);

const OAUTH2_CLIENT_ID = '791106018175614988';
const OAUTH2_CLIENT_SECRET = 'zQV10oh4g_eFsQ9AfVrxE9BuWmLdCUig';

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if (get('code') && get('state')) {
    $token = apiRequest($tokenURL, array(
        "grant_type" => "authorization_code",
        "client_id" => OAUTH2_CLIENT_ID,
        "client_secret" => OAUTH2_CLIENT_SECRET,
        "redirect_uri" => 'https://restorecord.com/api/discord',
        'code' => get('code')
    ));

    $_SESSION['access_token'] = $token->access_token;
    $_SESSION['state'] = get('state');

    header('Location: ' . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['access_token'], $_SESSION['username'], $_SESSION['state']) && !isset($_GET['code'])) {
    if ($_SESSION['state'] === "link") {
        $user = apiRequest("https://discord.com/api/users/@me");
        mysqli_query($link, "UPDATE `users` SET `userId` = '" . $user->id . "' WHERE `username` = '" . $_SESSION['username'] . "';");
        $json_data = json_encode([
            "content" => "" . $_SESSION['username'] . " linked account `" . $user->id . "`",
            "username" => "RestoreCord Logs",
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $username = $_SESSION['username'];
        ($result = mysqli_query($link, "SELECT role FROM `users` WHERE `username` = '$username'")) or die(mysqli_error($link));
        $row = mysqli_fetch_array($result);

        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bot ' . $token
        );
        $data = array("access_token" => session('access_token'));
        $data_string = json_encode($data, JSON_THROW_ON_ERROR);

        $ch = curl_init("https://discord.com/api/webhooks/901571010845872189/5kkbnUx0oFEocn2pHe8otDfmDGxD09DCZshICTF56DJRf622Dg8E-HHF45asci17WcV5");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);

        if ($row['role'] === "premium") {
            $url = "https://discord.com/api/guilds/785862036059979818/members/" . $user->id . "/roles/939535559146209300";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        } else if ($row['role'] === "business") {
            $url = "https://discord.com/api/guilds/785862036059979818/members/" . $user->id . "/roles/956242821222920212";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
        }


        die('
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script>
        var popup1337 = window.self;
        popup1337.opener.location.reload();
        popup1337.close();
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Success!</h4>
                    <p>You have successfully linked your Discord account to your RestoreCord account!</p>
                    <hr>
                    <p class="mb-0">Please close this page if it didn\'t already</p>
                </div>
            </div>
        </div>
    </div>
    ');
    }
} else if (isset($_SESSION['access_token'], $_SESSION['state']) && !isset($_GET['code'])) {
    if ($_SESSION['state'] === 'login') {
        $user = apiRequest("https://discord.com/api/users/@me");
        $result = mysqli_query($link, "SELECT username,email,role FROM `users` WHERE `userId` = '" . $user->id . "'");
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            $username = $row['username'];
            $email = $row['email'];
            $role = $row['role'];

            try {
                $json_data = json_encode([
                    "content" => "$username has logged in with ip `" . getIp() . "` (via Discord)",
                    "username" => "RestoreCord Logs",
                ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            } catch (Exception $e) {
                echo "ERROR REPORT THIS TO xenos#1337:\n$e";
            }

            $ch = curl_init("https://discord.com/api/webhooks/955952915296694312/plldkjchPN8MEq6Xu-CV4u2T7lYm8Mcg46Cn0hLQhqvHu9qWKeJsOf6VvDDK1tw8Rgya");
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-type: application/json'
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);

            curl_exec($ch);
            curl_close($ch);
            // webhook end

            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['pverif'] = "BTa3M3WDdcLogin";

            die('
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script>
        var popup1337 = window.self;
        popup1337.opener.location.reload();
        popup1337.close();
    </script>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Success!</h4>
                    <p>You have successfully Logged in as <code>' . $_SESSION['username'] . '</code>!</p>
                    <hr>
                    <p class="mb-0">Please close this page if it didn\'t already</p>
                </div>
            </div>
        </div>
    </div>
    ');
        }
    }
} else {
    die("<style>body { background: black; } .container { color: white; padding-top: 15rem; padding-left: 3rem; padding-right: 3rem;}</style><div class='container'>Not logged in with discord (try again or relogin).<br> if you're contacting support copy this code: <div style='margin-top: 1.5rem; background: #101010; border: solid #101010 0.5rem; border-radius: 0.75rem'><code>" . str_replace("=", "", strrev(base64_encode(json_encode($_SESSION, JSON_THROW_ON_ERROR))))) . "</code></div></div>";
}

?>
<html>

<head>
    <title>RestoreCord</title>
    <link rel="icon" type="image/png" href="https://cdn.restorecord.com/static/images/favicon.png">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
</head>
<style>
    body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
    }

    h1 {
        color: #88B04B;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-weight: 900;
        font-size: 40px;
        margin-bottom: 10px;
    }

    p {
        color: #404F5E;
        font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
        font-size: 20px;
        margin: 0;
    }

    i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left: -15px;
    }

    .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
    }
</style>

<body>
<div class="card">
    <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">&#10004;</i>
    </div>
    <h1>Success</h1>
    <p>please close this page, if there is an error contact support</p>
</div>
</body>

</html>