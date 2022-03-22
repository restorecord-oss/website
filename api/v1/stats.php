<?php
include '../../includes/connection.php';

$result = mysqli_query($link, "SELECT max(id) FROM users");
$row = mysqli_fetch_array($result);

$users = number_format($row[0]);

$result = mysqli_query($link, "SELECT max(id) FROM servers");
$row = mysqli_fetch_array($result);

$servers = number_format($row[0]);

$result = mysqli_query($link, "SELECT max(id) FROM members");
$row = mysqli_fetch_array($result);

$members = number_format($row[0]);

mysqli_close($link);

$stats = array('accounts' => $users, 'servers' => $servers, 'members' => $members);

header('Content-Type: application/json');

sleep(1);

echo json_encode($stats, JSON_THROW_ON_ERROR);
