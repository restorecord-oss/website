<?php

include '../../includes/connection.php';

/*
	This code is pretty much all from https://gist.github.com/Jengas/ad128715cb4f73f5cde9c467edf64b00
	It serves as the redirect page for users who 'Link Discord' in account settings
	Using their oauth2 access_token, it will first attempt to add them to the server, then give them a role if they're developer or seller
*/

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

// error_reporting(E_ALL);

define('OAUTH2_CLIENT_ID', '791106018175614988');
define('OAUTH2_CLIENT_SECRET', 'zQV10oh4g_eFsQ9AfVrxE9BuWmLdCUig');

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if (get('code')) {
    $token = apiRequest($tokenURL, array(
            "grant_type" => "authorization_code",
            "client_id" => OAUTH2_CLIENT_ID,
            "client_secret" => OAUTH2_CLIENT_SECRET,
            "redirect_uri" => 'http://localhost/api/discord',
            'code' => get('code'))
    );

    $_SESSION['access_token'] = $token->access_token;
    $_SESSION['state'] = get('state');

    header('Location: ' . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['access_token'], $_SESSION['username']) && !isset($_GET['code'])) {
    $user = apiRequest("https://discord.com/api/users/@me");
    $result = mysqli_query($link, "UPDATE `users` SET `userId` = '" . $user->id . "' WHERE `username` = '" . $_SESSION['username'] . "';");
    $json_data = json_encode(["content" => "" . $_SESSION['username'] . " linked account `" . $user->id . "`", "username" => "RestoreCord Logs",], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $username = $_SESSION['username'];
    ($result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username'")) or die(mysqli_error($link));
    $row = mysqli_fetch_array($result);

    $headers = array('Content-Type: application/json', 'Authorization: Bot ' . $token);
    $data = array("access_token" => session('access_token'));
    $data_string = json_encode($data);

    $ch = curl_init("https://discord.com/api/webhooks/901571010845872189/5kkbnUx0oFEocn2pHe8otDfmDGxD09DCZshICTF56DJRf622Dg8E-HHF45asci17WcV5");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);

    if ($row['role'] == "premium") {
        $url = "https://discord.com/api/guilds/785862036059979818/members/" . $user->id . "/roles/939535559146209300";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    } else if ($row['role'] == "business") {
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
} else {
    echo $_SESSION['access_token'] . "<br>";
    echo $_SESSION['username'] . "<br>";
    echo $_GET['code'] . "<br>";
    die("dead");
}

function apiRequest($url, $post = FALSE, $headers = array())
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $response = curl_exec($ch);


    if ($post) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    $headers[] = 'Accept: application/json';

    if (session('access_token')) $headers[] = 'Authorization: Bearer ' . session('access_token');

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response);
}

function get($key, $default = NULL)
{
    return array_key_exists($key, $_GET) ? $_GET[$key] : $default;
}

function session($key, $default = NULL)
{
    return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;
}

?>
<html>

<head>
    <title>RestoreCord - Member Restore Complete</title>
    <link rel="icon" type="image/png" href="https://keyauth.com/static/images/favicon.png">
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