<?php

use Mailjet\Client;

include '../includes/connection.php';
include '../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['username'])) {
    header("Location: ../dashboard/server/settings/");
    exit();
}

if (isset($_POST['email'])) {
    resetpww();
}

function resetpww()
{
    global $link;
    $recaptcha_response = sanitize($_POST['recaptcha_response']);
    $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Lcqx1weAAAAAPiN1x9BGVXswfn-ifNjOQtzVf3O&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha, false, 512, JSON_THROW_ON_ERROR);

    // if score is not there throw error
    if (!isset($recaptcha->score)) {
        box("Please disable your VPN or try using 1.1.1.1 DNS!", 3);
        return;
    }
    // Take action based on the score returned:
    if ($recaptcha->score < 0.5) {
        box("Human Check Failed!", 3);
        return;
    }

    $email = sanitize($_POST['email']);
    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$email'") or die(mysqli_error($link));
    if (mysqli_num_rows($result) == 0) {
        box("No account with this email!", 3);
        return;
    }

    $un = mysqli_fetch_array($result)['username'];

    $newPass = randomString(12);
    $newPassHashed = password_hash($newPass, PASSWORD_BCRYPT);
    $htmlContent = "
<html lang='en'>
<head>
    <title>RestoreCord - You Requested A Password Reset</title>
</head>

<body>
    <h1>We have reset your password</h1>
    <p>Your new password is: <b>$newPass</b></p>
    <p>Also, in case you forgot, your username is: <b>$un</b></p>
    <p>Login to your account and CHANGE your password for the best privacy.</p>
    <p style='margin-top: 20px;'>Thanks,<br><b>RestoreCord.</b></p>
</body>

</html>";

    require '../vendor/autoload.php';


    // export MJ_APIKEY_PUBLIC='2a035b8a2efdab6216da2129c8a637e3';
    // export MJ_APIKEY_PRIVATE='64134d8f0ba141259f3ca87812c60490';

    // Use your saved credentials, specify that you are using Send API v3.1

    $mj = new Client("2a035b8a2efdab6216da2129c8a637e3", "64134d8f0ba141259f3ca87812c60490", true, ['version' => 'v3.1']);

    // Define your request body

    $body = ['Messages' => [['From' => ['Email' => "noreply@restorecord.com", 'Name' => "RestoreCord"], 'To' => [['Email' => (string)$email, 'Name' => (string)$un]], 'Subject' => "RestoreCord - Password Reset", 'HTMLPart' => $htmlContent]]];

    // All resources are located in the Resources class

    $response = $mj->post(Mailjet\Resources::$Email, ['body' => $body]);

    mysqli_query($link, "UPDATE `users` SET `password` = '$newPassHashed' WHERE `email` = '$email'") or die(mysqli_error($link));
    box("Please check your email, I sent password. (Check Spam Too!)", 2);

}

?>

<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Forgot Password</title>
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

    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/vendors/css/vendors.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/themes/semi-dark-layout.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/core/menu/menu-types/vertical-menu.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/plugins/forms/form-validation.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/app-assets/css/pages/authentication.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6Lcqx1weAAAAAItfxuTTU-iodSGuQ0l6HUVErTkv"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6Lcqx1weAAAAAItfxuTTU-iodSGuQ0l6HUVErTkv', {
                action: 'contact'
            }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>

</head>


<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <div class="auth-wrapper auth-basic px-2">
                <div class="auth-inner my-2">
                    <div class="card mb-0">
                        <div class="card-body">
                            <a href="/" class="brand-logo">
                                <h2 class="brand-text text-primary ms-1">RestoreCord</h2>
                            </a>

                            <h4 class="card-title mb-1">Forgot Password? 🔒</h4>
                            <p class="card-text mb-2">Enter your email and we'll send you instructions to reset your password</p>

                            <form class="auth-forgot-password-form mt-2" method="POST">
                                <div class="mb-1">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="john@example.com" aria-describedby="email" tabindex="1" autofocus />
                                </div>

                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                                <button class="btn btn-primary w-100" tabindex="2">Reset Password</button>
                            </form>

                            <p class="text-center mt-2">
                                <a href="/login"> <i data-feather="chevron-left"></i> Back to login </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.restorecord.com/app-assets/vendors/js/vendors.min.js"></script>

<script src="https://cdn.restorecord.com/app-assets/vendors/js/forms/validation/jquery.validate.min.js"></script>

<script src="https://cdn.restorecord.com/app-assets/js/core/app-menu.js"></script>
<script src="https://cdn.restorecord.com/app-assets/js/core/app.js"></script>

<script src="https://cdn.restorecord.com/app-assets/js/scripts/pages/auth-forgot-password.js"></script>

<script>
    $(window).on('load', function () {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })

</script>
</body>

</html>