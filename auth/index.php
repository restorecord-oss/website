<?php

include '../includes/connection.php';
include '../includes/functions.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if (isset($_SESSION['owner'], $_SESSION['name']) && !get('state') && get('code') && strlen(get('code')) === 30) {
    $token = apiRequest("https://discord.com/api/oauth2/token", array(
        'grant_type' => 'authorization_code',
        'client_id' => '791106018175614988',
        'client_secret' => 'zQV10oh4g_eFsQ9AfVrxE9BuWmLdCUig',
        'redirect_uri' => 'https://restorecord.com/auth/',
        'code' => get('code')
    ));

    $_SESSION['access_token'] = $token->access_token;
    $_SESSION['refresh_token'] = $token->refresh_token;

    $server = $_SESSION['owner'] . '/' . $_SESSION['name'];
    header('Location: https://restorecord.com/verify/' . $server);
} else if (get('state') && get('code') && strlen(get('code')) === 30) {
    $token = apiRequest("https://discord.com/api/oauth2/token", array(
        'grant_type' => 'authorization_code',
        'client_id' => '791106018175614988',
        'client_secret' => 'zQV10oh4g_eFsQ9AfVrxE9BuWmLdCUig',
        'redirect_uri' => 'https://restorecord.com/auth/',
        'code' => get('code')
    ));

    $_SESSION['access_token'] = $token->access_token;
    $_SESSION['refresh_token'] = $token->refresh_token;

    header('Location: https://restorecord.com/verify/?guild=' . get('state'));
}

die("invalid request, please retry verification process");

?>