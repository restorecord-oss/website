<?php
include '../includes/connection.php';
include '../includes/functions.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$message = "";

if (isset($_SESSION['username'])) {
    header("Location: ../dashboard/server/settings/");
    exit();
}

if (isset($_POST['username'], $_POST['password'], $_POST['email'])) {
    register();
}

function register()
{
    global $link;
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

        $recaptcha_response = sanitize($_POST['recaptcha_response']);
        $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Lcqx1weAAAAAPiN1x9BGVXswfn-ifNjOQtzVf3O&response=' . $recaptcha_response);
        $recaptcha = json_decode($recaptcha, false, 512, JSON_THROW_ON_ERROR);

        if (!isset($recaptcha)) {
            box('Human Verification Failed, please turn off your VPN and try again.', 3);
            return;
        }

        if ($recaptcha->score < 0.5) {
            box('Human Verification Failed.', 3);
            return;
        }


    $username = sanitize($_POST['username']);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        box('Username can only contain letters, numbers and underscores.', 3);
        return;
    }

    $password = sanitize($_POST['password']);

    $email = sanitize($_POST['email']);

    $result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username'") or die(mysqli_error($link));

    if (mysqli_num_rows($result) === 1) {
        box("Username already taken!", 3);
        return;
    }

    $email_check = mysqli_query($link, "SELECT * FROM `users` WHERE `email` = '$email'") or die(mysqli_error($link));
    $do_email_check = mysqli_num_rows($email_check);
    if ($do_email_check > 0) {
        box('Email already in use by: ' . mysqli_fetch_array($email_check)['username'], 3);
        return;
    }
    $pass_encrypted = password_hash($password, PASSWORD_BCRYPT);

    $paidusers = array(
        "archiehazle69@gmail.com",
        "megaclart@gmail.com",
        "noh4ndo@gmail.com",
        "eliassolomonides0@gmail.com",
        "lunalotus303@gmail.com",
        "bloodofsniper9@gmail.com",
        "visual2353525235@gmail.com",
        "antim8sam@gmail.com",
        "x3hy161@gmail.com",
        "caztokarczyk2008@gmail.com",
        "prinz20005@gmail.com",
        "leoamplayen@gmail.com",
        "laskfoul@gmail.com",
        "atomicss@protonmail.com",
        "dateigreen2@gmail.com",
        "kaciyoung421@gmail.com",
        "nclan227@gmail.com",
        "henriksday@gmail.com",
        "ob3yxnuke@gmail.com",
        "kenealex09@gmail.com",
        "welpnate@gmail.com",
        "ehapostal04@gmail.com",
        "kevinlu135@gmail.com",
        "yang91660@gmail.com",
        "airportpickuplondo@gmail.com",
        "laraang12345@gmail.com",
        "tkfq242@gmail.com",
        "danmobile038@gmail.com",
        "sintiaqueiroz427@gmail.com",
        "maxi2000minecraft@gmail.com",
        "stephensimban989@yahoo.com",
        "tommii.j1996@gmail.com",
        "snipcola@gmail.com",
        "gersonr1538@gmail.com",
        "vitus@skjoldjensen.dk",
        "anthonydanielt07@gmail.com",
        "0515151@dadeschools.net",
        "jennygwyther@gmail.com",
        "seppesels20@gmail.com",
        "frrberbgrbgrbg@gmail.com",
        "brysoneschbach07@gmail.com",
        "holte1740@gmail.com",
        "breixopd14@gmail.com",
        "98494977az@gmail.com",
        "xjimmymemecord@gmail.com",
        "nullydiscord@gmail.com",
        "lionelgamer@gmx.de",
        "braydenapps@icloud.com",
        "stevierrodriguez@gmail.com",
        "jacbenimble2@gmail.com",
        "universehvh@gmail.com",
        "jadawi.013@gmail.com",
        "xakexdk@gmail.com",
        "qwdqwdqwe@a.com",
        "giorgi.pailodze@yahoo.com",
        "artixdiscord2@gmail.com",
        "haynesjordan470@gmail.com",
        "damian.tna.cruz@gmail.com",
        "aryanjha635@gmail.com",
        "rodrigomacias2087@gmail.com",
        "zaidanisagamer@gmail.com",
        "79zl3n12sifr@maskme.us",
        "maciachristopher24@gmail.com",
        "killowattcheats@gmail.com",
        "yournan@gmail.com",
        "sllimekez@gmail.com",
        "isaacriehm1@gmail.com",
        "andrej5154@seznam.cz",
        "evanhennesey@gmail.com",
        "diamodman1955@gmail.com",
        "epicgamersonly123@gmail.com",
        "colton.hieu.meador@gmail.com",
        "cartiieer3@gmail.com",
        "jadenrender939@protonmail.com",
        "glockritter@gmail.com",
        "milanimkohl@gmail.com",
        "tobio3690@gmail.com",
        "uselessemail158@gmail.com",
        "b4uarmy@protonmail.com",
        "lolmanlolman555@gmail.com",
        "jespers457@gmail.com",
        "premium11romeo@gmail.com",
        "Tristanisabruh@gmail.com",
        "ganeshbrandon500@gmail.com",
        "hi@exec.gq",
        "activelag2017@gmail.com",
        "l0w4nyu@gmail.com",
        "realitynova282@gmail.com",
        "jynxzy9062@gmail.com",
        "fizzypsn11@gmail.com",
        "pandherfateh@gmail.com",
        "eruchavez0.3@gmail.com",
        "fahadsheikhx@gmail.com",
        "ncucchiara26@gmail.com",
        "jerorcaden@gmail.com",
        "urosjeremic321@gmail.com",
        "terelle993@gmail.com",
        "tommymorton34@outlook.com",
        "kuahy5969@gmail.com",
        "minerdallasgaming@gmail.com",
        "xbruno.martins@live.com",
        "elie.salhany@outlook.com",
        "hneesunchee@gmail.com",
        "Kudosore@gmail.com",
        "syzm3kflis@gmail.com",
        "UberPabloTV@gmail.com",
        "soulgamingyt1@gmail.com",
        "brysxnardon@gmail.com",
        "cyberlobbycodwar@gmail.com",
        "vixrust@gmail.com",
        "chelsea.rvx@gmail.com",
        "animeisadev@gmail.com",
        "gamerzpartner@gmail.com",
        "arty@creativeproxies.com",
        "tartarsauce41@gmail.com",
        "jimmydelazerna321@gmail.com",
        "reeceraweu@gmail.com",
        "earlystefke@gmail.com",
        "abanoubgiris@gmail.com",
        "mono2lith@gmail.com",
        "sidiousalliance@gmail.com",
        "Kushy5969@gmail.com",
        "obetzonplug@gmail.com",
        "makingufree@outlook.com",
        "mirya6987@gmail.com",
        "anlistofikrian@gmail.com",
        "Scottywhitey@yahoo.com",
        "anthonyrphipps@gmail.com",
        "charleshartman06@gmail.com",
        "Averyg710@gmail.com",
        "billie@bloh.sh",
        "caturner69@gmail.com",
        "tvchannelpromo@gmail.com",
        "Habosrie@gmail.com",
        "othm1009@gmail.com",
        "tristangrsge@gmail.com",
        "b3tagaming692@gmail.com",
        "karma42018@gmail.com",
        "wlsnsrvcs@gmail.com",
        "TheWhiteHatCheater@gmail.com",
        "ace@aceservices.shop",
        "uk.wso2006@gmail.com",
        "mightyatshop@gmail.com",
        "zaidocr@gmail.com",
        "onebreadhax@gmail.com",
        "hassanlemars@gmail.com",
        "nitrosnekrosigetis@gmail.com",
        "acerat105@gmail.com",
        "potegarcia05@gmail.com",
        "aaronaj222@live.com",
        "jareddicus@icloud.com",
        "kaceip@gmail.com",
        "mattdaignault14@gmail.com",
        "tom.j.shillito@gmail.com",
        "rabbadi3939@gmail.com",
        "pkmodz.com@gmail.com",
        "eliassolomonides0@gmail.com",
        "scatterhvh@gmail.com",
        "gamingwithgabe2002@gmail.com",
        "jordanberiana63@gmail.com",
        "ramenV7@outlook.com",
        "spencerfarrell32@gmail.com",
        "senpaitrey@gmail.com",
        "viniciuslimajoinville@gmail.com",
        "banno_greg@yahoo.com",
        "LucidDreamAlt01@protonmail.com",
        "newfortemail@gmail.com",
        "napsterboost@gmail.com",
        "noahhand@mail.com",
        "flickmor@protonmail.com",
        "balint.andrei2019@gmail.com",
        "maffank92@gmail.com",
        "Chrisw33560@gmail.com",
        "PrelacyJ@outlook.com",
        "zootedmods@gmail.com",
        "Siccopaypal1@gmail.com",
        "Anth161616@hotmail.co.uk",
        "dmitry.mynett@gmail.com",
        "coranmitchell2021@gmail.com",
        "ayden241@hotmail.com",
        "guzguzjesse@gmail.com",
        "gomcakesservices@gmail.com",
        "arabitsherr@gmail.com",
        "rivaldivision123@gmail.com",
        "psychil@krimnizo.com",
        "samgefx@gmail.com"
    );
    if (in_array($email, $paidusers, true)) {
        $time = time() + 31556926;
        mysqli_query($link, "INSERT INTO `users` (`username`, `email`, `password`, `role`,`expiry`) VALUES ('$username', '$email', '$pass_encrypted', 'premium', '$time')") or die(mysqli_error($link));
    } else {
        mysqli_query($link, "INSERT INTO `users` (`username`, `email`, `password`) VALUES ('$username', '$email', '$pass_encrypted')") or die(mysqli_error($link));
    }

    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = 'free';
    $_SESSION['pverif'] = $password;

    header("location: /dashboard");
}


?>


<!DOCTYPE html>
<html class="loading dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Register</title>
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

                            <form class="auth-register-form mt-2" method="POST">
                                <div class="mb-1">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="johndoe" aria-describedby="register-username" tabindex="1" autofocus/>
                                </div>
                                <div class="mb-1">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="john@example.com" aria-describedby="register-email" tabindex="2"/>
                                </div>

                                <div class="mb-1">
                                    <label for="password" class="form-label">Password</label>

                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input type="password" class="form-control form-control-merge" id="password" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="register-password" tabindex="3"/>
                                        <span class="input-group-text cursor-pointer">
                                            <i data-feather="eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">


                                <div class="mb-1">
                                    <label class="form-label" for="terms">
                                        All users are bound to the
                                        <a href="/terms">terms</a>
                                        &
                                        <a href="/privacy">privacy policy</a>
                                    </label>
                                </div>
                                <button class="btn btn-primary w-100" tabindex="5" type="submit">Sign up</button>
                            </form>

                            <p class="text-center mt-2">
                                <span>Already have an account?</span>
                                <a href="/login">
                                    <span>Sign in instead</span>
                                </a>
                            </p>
                        </div>
                    </div>
                    <!-- /Register basic -->
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
<script src="https://cdn.restorecord.com/app-assets/js/scripts/pages/auth-register.js"></script>
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
</script>
</body>
<!-- END: Body-->

</html>