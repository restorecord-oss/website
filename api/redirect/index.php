<?php

if (session_status() === PHP_SESSION_NONE)
{
    session_start();
}

include '../../includes/connection.php';
include '../../includes/functions.php';

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$uri = trim($_SERVER['REQUEST_URI'], '/');
$pieces = explode('/', $uri);
$owner = urldecode(sanitize($pieces[2]));
$server = urldecode(sanitize($pieces[3]));

if(is_null($owner) || is_null($server))
{
	die("Invalid link. Link should look like https://restorecord.com/api/redirect/{owner}/{server}");
}

$result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '$owner' AND `name` = '$server'");

if (mysqli_num_rows($result) === 0)
{
    die("No Server Found");
}
else
{
    // while ($row = mysqli_fetch_array($result))
    // {
    //     $guildid = $row['guildid'];
    // }

    // $_SESSION['server'] = $guildid;
    $_SESSION['owner'] = $owner;
    $_SESSION['name'] = $server;
 
	header("Location: https://discord.com/api/oauth2/authorize?client_id=791106018175614988&redirect_uri=https%3A%2F%2Frestorecord.com%2Fauth%2F&response_type=code&scope=identify+guilds.join");
}
?>