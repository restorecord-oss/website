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
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function (event) {
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

    global $link; // needed to reference active MySQL connection
    //return $input;
    return mysqli_real_escape_string($link, htmlspecialchars($input));
    //return mysqli_real_escape_string($link, strip_tags(trim($input))); // return string with quotes escaped to prevent SQL injection, script tags stripped to prevent XSS attach, and trimmed to remove whitespace
    //return strip_tags(trim($input));
}

function heador()
{
    ?>
    <form class="text-left" method="POST">
        <p class="mb-4">Name:
            <br><?php echo $_SESSION['server_to_manage']; ?><br/>
        <div class="mb-4">Verify Link:
            <br><a href="<?php echo "https://restorecord.com/verify/" . urlencode($_SESSION['username']) . "/" . urlencode($_SESSION['server_to_manage']); ?>"
                   style="color:#00FFFF;"
                   target="verifylink"><?php echo "https://restorecord.com/verify/" . urlencode($_SESSION['username']) . "/" . urlencode($_SESSION['server_to_manage']); ?></a><br/>
        </div>
        <a style="color:#4e73df;cursor: pointer;" id="mylink">Change</a>
        <button style="border: none;padding:0;background:0;color:#FF0000;padding-left:5px;" name="deleteserver"
                onclick="return confirm('Are you sure you want to delete server and all associated members?')">Delete
        </button>
        </p>
    </form>
    <?php
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
            box("Successfully deleted Server!", 2);
        } else {
            box("Server Deletion Failed!", 3);
        }
    }


    function renameServer()
    {
        global $link;
        $name = sanitize($_POST['name']);

        $result = mysqli_query($link, "SELECT * FROM `servers` WHERE `owner` = '" . $_SESSION['username'] . "' AND `name` = '$name'");
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            box("You already have a server with this name!", 3);
            return;
        }

        $server = sanitize($_SESSION['server_to_manage']);

        mysqli_query($link, "UPDATE `servers` SET `name` = '$name' WHERE `name` = '$server' AND `owner` = '$server'");

        $_SESSION['server_to_manage'] = $name;

        if (mysqli_affected_rows($link) !== 0) {
            box("Successfully Renamed Server!", 2);
        } else {
            box("Server Rename Failed!", 3);
        }
    }

    function createApp()
    {
        global $link;
        global $role;
        $appname = sanitize($_POST['appname']);

        if (strlen($appname) > 20) {
            mysqli_close($link);
            box("Character limit for server name is 20 characters, please try again with shorter name.", 3);
            return;
        }

        if (strlen($appname) < 3) {
            mysqli_close($link);
            box("Character limit for server name is 3 characters, please try again with longer name.", 3);
            return;
        }


        $result = mysqli_query($link, "SELECT * FROM servers WHERE name='$appname' AND owner='" . $_SESSION['username'] . "'");
        if (mysqli_num_rows($result) > 0) {
            mysqli_close($link);
            box("You already own server with this name!", 3);
            return;
        }

        $owner = $_SESSION['username'];

        if ($role === "free") {
            $result = mysqli_query($link, "SELECT * FROM servers WHERE owner='$owner'");

            if (mysqli_num_rows($result) > 0) {
                mysqli_close($link);
                box("Free plan only supports one server!", 3);
                return;
            }

        }

        mysqli_query($link, "INSERT INTO `servers`(`owner`, `name`, `pic`) VALUES ('$owner','$appname','https://i.imgur.com/w65Dpnw.png')");
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
            if (imagecolorsforindex($image, imagecolorstotal($image) - 1) ['alpha'] == 127) {
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

function vpncheck($ip, $webhook, $user)
{
    $url = "https://proxycheck.io/v2/{$ip}?key=0j7738-281108-49802e-55d520?vpn=1";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $json = json_decode($result);
    if ($json->$ip->proxy == "yes") {
        if (!is_null($webhook)) {
            /*
                WEBHOOK START
            */

            $timestamp = date("c", strtotime("now"));
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

function get_timeago($ptime)
{
    $estimate_time = time() - $ptime;

    if ($estimate_time < 1) {
        return 'less than 1 second ago';
    }

    $condition = array(12 * 30 * 24 * 60 * 60 => 'year', 30 * 24 * 60 * 60 => 'month', 24 * 60 * 60 => 'day', 60 * 60 => 'hour', 60 => 'minute', 1 => 'second');

    foreach ($condition as $secs => $str) {
        $d = $estimate_time / $secs;

        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}


function sidebar($admin)
{
    ?>
    <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Server</span></li>
    <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="/dashboard/server/settings/" aria-expanded="false"><i data-feather="settings"></i><span
                    class="hide-menu">Settings</span></a></li>
    <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="/dashboard/server/members/" aria-expanded="false"><i
                    data-feather="users"></i><span class="hide-menu">Members</span></a></li>
    <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="/dashboard/server/blacklist/" aria-expanded="false"><i
                    data-feather="user-x"></i><span class="hide-menu">Blacklist</span></a></li>
    <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Account</span></li>
    <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="/dashboard/account/settings/" aria-expanded="false"><i
                    data-feather="settings"></i><span class="hide-menu">Settings</span></a></li>
    <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="/dashboard/account/upgrade/" aria-expanded="false"><i data-feather="activity"></i><span
                    class="hide-menu">Upgrade</span></a></li>
    <?php
    if ($admin) {
        ?>
        <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Admin</span></li>
        <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark sidebar-link" href="/admin/"
                                    aria-expanded="false"><i data-feather="move"></i><span
                        class="hide-menu">Panel</span></a></li>
        <?php
    }
}

function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function error($msg)
{
    echo '<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script type=\'text/javascript\'>
        const notyf = new Notyf();
        notyf.error({
            message: \'' . $msg . '\',
            duration: 3500,
            dismissible: true
        });
    </script>';
}

function success($msg)
{
    echo '<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script type=\'text/javascript\'>
        const notyf = new Notyf();
        notyf.success({
            message: \'' . $msg . '\',
            duration: 3500,
            dismissible: true
        });               
    </script>';
}

function premium_check($username)
{
    global $link; // needed to refrence active MySQL connection
    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username' AND `role` = 'premium'");
    if (mysqli_num_rows($result) === 1) {
        $expiry = mysqli_fetch_array($result)["expiry"];
        if ($expiry < time()) {
            mysqli_query($link, "UPDATE `users` SET `role` = 'free' WHERE `username` = '$username'");
        }
    }
}

function test($username, $pw)
{
    global $link;
    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_array($result);
        if (!password_verify($pw, $row['password'])) {
            session_unset();
            session_destroy();
            echo "<meta http-equiv='Refresh' Content='0; url=/login'>";
            die();
        }
    }
}

/**
 * @throws JsonException
 */
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

/**
 * @throws JsonException
 */
function wh_log($webhook_url, $msg, $un)
{
    if (empty($webhook_url)) {
        return;
    }

    $json_data = json_encode(["content" => $msg, "username" => (string)$un,

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