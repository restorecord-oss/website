<?php

use Mailjet\Client;

include '../includes/connection.php';
include '../includes/functions.php';


if (isset($_SESSION['username'])) {
    header("Location: ../dashboard/server/settings/");
    exit();
}

if (isset($_POST['login'])) {
    login();
}

function login()
{
    global $link;
    if (session_status() === PHP_SESSION_NONE) {
        if (isset($_POST['remember-me'])) {
            ini_set('session.cookie_lifetime', 604800); // 1 week
            ini_set('session.gc_maxlifetime', 604800); // 1 week
            session_set_cookie_params(604800); // 1 week
        }
        session_start();
    }

    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    ($result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username'")) or die(mysqli_error($link));

    if (mysqli_num_rows($result) === 0) {
        box("Account doesn\'t exist!", 3);
        return;
    }
    while ($row = mysqli_fetch_array($result)) {
        $pass = $row['password'];
        $email = $row['email'];
        $role = $row['role'];
        $banned = $row['banned'];
        $last_ip = $row['last_ip'];

        $twofactor_optional = $row['twofactor'];
        $google_Code = $row['googleAuthCode'];
    }

    if (!is_null($banned)) {
        box("Banned: Reason: " . sanitize($banned), 3);
        return;
    }

    if (!password_verify($password, $pass)) {
        box("Password is invalid!", 3);
        return;
    }

    if ($twofactor_optional) {
        $twofactor = sanitize($_POST['twofactor']);
        if (empty($twofactor)) {
            box("Two factor field needed for this acccount!", 3);
            return;
        }

        require_once '../auth/GoogleAuthenticator.php';
        $gauth = new GoogleAuthenticator();
        $checkResult = $gauth->verifyCode($google_Code, $twofactor, 2);

        if (!$checkResult) {
            box("2FA code Invalid!", 3);
            return;
        }
    }

    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

    if ($last_ip === NULL) {
        mysqli_query($link, "UPDATE `users` SET `last_ip` = '$ip' WHERE `username` = '$username'") or die(mysqli_error($link));
    } else if ($last_ip !== $ip) {
        mysqli_query($link, "UPDATE `users` SET `last_ip` = '$ip' WHERE `username` = '$username'") or die(mysqli_error($link));
        $details = json_decode(file_get_contents("https://ipinfo.io/$ip?token=871723f6a65a43"), false, 512, JSON_THROW_ON_ERROR);
        $htmlContent = '
        <html>
        <head>
            <title>RestoreCord - Login from new Location</title>
        </head>
        <body>
            <div style="background-color:#f9f7ff">
                <div>
                    <div style="margin:0 auto;max-width:570px">
                        <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%">
                            <tbody>
                                <tr style="vertical-align:top">
                                    <td height="130" style="vertical-align: top;padding-top:0;padding-left:0;padding-right:0;padding-bottom: 0;background: url(https://i.imgur.com/VDY9rlb.png) no-repeat center;background-size: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin:0 auto;max-width:570px;background:#ffffff">
                        <table role="presentation" cellpadding="0" cellspacing="0"
                            style="font-size:0;width:100%;background:#ffffff">
                            <tbody>
                                <tr>
                                    <td
                                        style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:20px 0">
                                        <div
                                            style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px">
                                                            <div
                                                                style="color:#000000;font-family:Roboto,sans-serif;font-size:14px;font-weight:400;line-height:1.5;text-align:left">
                                                                <br>Hey ' . $username . ',<br>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px">
                                                            <div style="color:#000000;font-family:Roboto,sans-serif;font-size:14px;font-weight:400;line-height:1.5;text-align:left">
                                                                Successful Login from a new location. If this was you, you can safely ignore this message. If this was <strong>not</strong> you, Contact support immediately, and change your password.<br><br>
                                                                <strong>IP Address:</strong> ' . $details->ip . '<br>
                                                                <strong>Location:</strong> ' . $details->city . ', ' . $details->region . ', ' . $details->country . '<br><br>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px">
                                                            <div style="color:#000000;font-family:Roboto,sans-serif;font-size:14px;font-weight:400;line-height:1.5;text-align:left">
                                                                Best Regards, <br> RestoreCord Team<br>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="margin:0 auto;max-width:570px;background:#f9f7ff">
                        <table role="presentation" cellpadding="0" cellspacing="0"
                            style="font-size:0;width:100%;background:#f9f7ff">
                            <tbody>
                                <tr>
                                    <td
                                        style="text-align:center;vertical-align:top;direction:ltr;font-size:0;padding:20px 0">
                                        <div class="m_162601587205469835mj-column-per-100"
                                            style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
                                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px"></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px">
                                                            <div
                                                                style="color:#8f8e93;font-family:Roboto,sans-serif;font-size:12px;font-weight:400;line-height:1.5;text-align:left">
                                                                Use of this Website constitutes acceptance of the
                                                                <a href="https://restorecord.com/terms"style="color: #7388db;text-decoration:none;"target="_blank">Terms of Service</a> and <a href="https://restorecord.com/privacy"style="color: #7388db;text-decoration:none;"target="_blank">Privacy policy.</a> All copyrights, trade
                                                                marks, service
                                                                marks belong to the corresponding owners.</div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="word-wrap:break-word;font-size:0;padding:10px 25px">
                                                            <div
                                                                style="color:#8f8e93;font-family:Roboto,sans-serif;font-size:12px;font-weight:400;line-height:1.5;text-align:left">
                                                                In case of any questions you can contact us at <a
                                                                    href="mailto:support@restorecord.com"
                                                                    style="color: #7388db;text-decoration:none;"
                                                                    target="_blank">support@restorecord.com</a></div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ';

        require '../vendor/autoload.php';

        $mj = new Client("2a035b8a2efdab6216da2129c8a637e3", "64134d8f0ba141259f3ca87812c60490", true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "noreply@restorecord.com",
                        'Name' => "RestoreCord"
                    ],
                    'To' => [
                        [
                            'Email' => (string)$email,
                            'Name' => (string)$username
                        ]
                    ],
                    'Subject' => "RestoreCord - Login from new Location",
                    'HTMLPart' => $htmlContent
                ]
            ]
        ];
        $mj->post(Mailjet\Resources::$Email, ['body' => $body]);

        mysqli_query($link, "UPDATE `users` SET `last_ip` = '$ip' WHERE `username` = '$username'") or die(mysqli_error($link));

    }

    if (password_verify($password, $pass)) {
        box("Successfully logged in!", 3);
        // webhook
        $json_data = json_encode([
            "content" => "$username has logged in with ip `" . $_SERVER['HTTP_CF_CONNECTING_IP'] . "`",
            "username" => "RestoreCord Logs",
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

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
        $_SESSION['pverif'] = $password;

        header("location: /dashboard");
    }
}

?>


<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Login</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/vendors/css/vendors.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/themes/semi-dark-layout.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/pages/authentication.css">
    <!-- END: Page CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="auth-wrapper auth-basic px-2">
                <div class="auth-inner my-2">
                    <!-- Login basic -->
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="/" class="brand-logo">
                                <h2 class="brand-text text-primary ms-1">RestoreCord</h2>
                            </a>
                            <form class="auth-login-form mt-2" method="POST">
                                <div class="mb-1">
                                    <label for="login-email" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="johndoe" aria-describedby="username" tabindex="1" autofocus/>
                                </div>

                                <div class="mb-1">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="login-password">Password</label>
                                        <a href="/forgot">
                                            <small>Forgot Password?</small>
                                        </a>
                                    </div>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="password" name="password" tabindex="2" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password"/>
                                        <span class="input-group-text cursor-pointer">
                                            <i data-feather="eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-1">
                                    <label for="login-email" class="form-label">2FA Code (Optional)</label>
                                    <input type="text" class="form-control" id="twofactor" name="twofactor" placeholder="123456" aria-describedby="twofactor" tabindex="3"/>
                                </div>

                                <div class="mb-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember-me" name="remember-me" tabindex="4"/>
                                        <label class="form-check-label" for="remember-me"> Remember Me</label>
                                    </div>
                                </div>
                                <button class="btn btn-primary w-100" tabindex="5" type="submit" name="login">Sign in</button>
                            </form>

                            <p class="text-center mt-2">
                                <span>New to RestoreCord?</span>
                                <a href="/register">
                                    <span>Create an account</span>
                                </a>
                            </p>

                            <div class="divider my-2">
                                <div class="divider-text">or</div>
                            </div>

                            <div class="auth-footer-btn d-flex justify-content-center">
                                <a class="btn btn-facebook w-100" style="color: #fff; background-color: #5865F2;" href="javascript:newPopup('https://discord.com/api/oauth2/authorize?client_id=791106018175614988&redirect_uri=https://restorecord.com/api/discord&response_type=code&scope=identify&prompt=none&state=login');">Login With Discord
                                    <svg role="img" width="14" height="14" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#fff">
                                        <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3933-.4058-.8742-.6177-1.2495a.077.077 0 00-.0785-.037 19.7363 19.7363 0 00-4.8852 1.515.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 01.0776-.0105c3.9278 1.7933 8.18 1.7933 12.0614 0a.0739.0739 0 01.0785.0095c.1202.099.246.1981.3728.2924a.077.077 0 01-.0066.1276 12.2986 12.2986 0 01-1.873.8914.0766.0766 0 00-.0407.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0552c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /Login basic -->
                </div>
            </div>

        </div>
    </div>
</div>
<!-- END: Content-->


<!-- BEGIN: Vendor JS-->
<script src="https://cdn.restorecord.com/app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="https://cdn.restorecord.com/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="https://cdn.restorecord.com/app-assets/js/core/app-menu.js"></script>
<script src="https://cdn.restorecord.com/app-assets/js/core/app.js"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="https://cdn.restorecord.com/app-assets/js/scripts/pages/auth-login.js"></script>
<!-- END: Page JS-->

<script>
    $(window).on('load', function () {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })

    function newPopup(url) {
        const popupWindow = window.open(
            url,
            'popUpWindow',
            `menubar=no,width=500,height=777,resizable=no,scrollbars=yes,status=no,top=` + (screen.height - 877) /
            2 + ',left=' + (screen.width - 500) / 2);
        popupWindow.focus();

        const timer = setInterval(function () {
            if (popupWindow.closed) {
                clearInterval(timer);
                window.location.reload();
            }
        }, 1000);

    }

</script>
</body>
<!-- END: Body-->

</html>