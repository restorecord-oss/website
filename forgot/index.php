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

if (isset($_POST['reset'])) {
    resetpww();
}

function resetpww()
{
    global $link;
    $recaptcha_response = sanitize($_POST['recaptcha_response']);
    $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Lcqx1weAAAAAPiN1x9BGVXswfn-ifNjOQtzVf3O&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

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

    $newPass = rand();
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
    <p>Login to your account and change your password for the best privacy.</p>
    <p style='margin-top: 20px;'>Thanks,<br><b>RestoreCord.</b></p>
</body>

</html>";

    require '../vendor/autoload.php';


    // export MJ_APIKEY_PUBLIC='2a035b8a2efdab6216da2129c8a637e3';
    // export MJ_APIKEY_PRIVATE='64134d8f0ba141259f3ca87812c60490';

    // Use your saved credentials, specify that you are using Send API v3.1

    $mj = new Client("2a035b8a2efdab6216da2129c8a637e3", "64134d8f0ba141259f3ca87812c60490", true, ['version' => 'v3.1']);

    // Define your request body

    $body = ['Messages' => [['From' => ['Email' => "noreply@restorecord.com", 'Name' => "RestoreCord"], 'To' => [['Email' => "$email", 'Name' => "$un"]], 'Subject' => "RestoreCord - Password Reset", 'HTMLPart' => $htmlContent]]];

    // All resources are located in the Resources class

    $response = $mj->post(Mailjet\Resources::$Email, ['body' => $body]);

    mysqli_query($link, "UPDATE `users` SET `password` = '$newPassHashed' WHERE `email` = '$email'") or die(mysqli_error($link));
    box("Please check your email, I sent password. (Check Spam Too!)", 2);

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>RestoreCord - Forgot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://cdn.restorecord.com/assets/img/favicon.png" type="image/x-icon">
    <style>
        body {
            background-color: #232323;
            color: #a6a6a6;
        }

        .p-t-3 {
            padding-top: 3px;
        }

        .p-t-50 {
            padding-top: 50px;
        }

        .p-b-24 {
            padding-bottom: 24px;
        }

        .p-b-51 {
            padding-bottom: 51px;
        }

        .p-b-90 {
            padding-bottom: 90px;
        }

        .m-t-17 {
            margin-top: 17px;
        }

        .m-b-16 {
            margin-bottom: 16px;
        }

        .w-full {
            width: 100%;
        }

        .wrap-pic-w img {
            width: 100%;
        }

        .wrap-pic-max-w img {
            max-width: 100%;
        }

        .wrap-pic-h img {
            height: 100%;
        }

        .wrap-pic-max-h img {
            max-height: 100%;
        }

        .wrap-pic-cir img {
            width: 100%;
        }

        .hov-img-zoom img {
            width: 100%;
            -webkit-transition: all 0.6s;
            -o-transition: all 0.6s;
            -moz-transition: all 0.6s;
            transition: all 0.6s;
        }

        .hov-img-zoom:hover img {
            -webkit-transform: scale(1.1);
            -moz-transform: scale(1.1);
            -ms-transform: scale(1.1);
            -o-transform: scale(1.1);
            transform: scale(1.1);
        }

        .flex-w {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-flex-wrap: wrap;
            -moz-flex-wrap: wrap;
            -ms-flex-wrap: wrap;
            -o-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        .flex-sb {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: space-between;
        }

        .flex-sb-m {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: space-between;
            -ms-align-items: center;
            align-items: center;
        }


        /*//////////////////////////////////////////////////////////////////
        [ FONT ]*/

        @font-face {
            font-family: Ubuntu-Regular;
            src: url('https://cdn.restorecord.com/auth/fonts/ubuntu/Ubuntu-Regular.ttf');
        }

        @font-face {
            font-family: Ubuntu-Bold;
            src: url('https://cdn.restorecord.com/auth/fonts/ubuntu/Ubuntu-Bold.ttf');
        }

        /*//////////////////////////////////////////////////////////////////
        [ RESTYLE TAG ]*/

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: Ubuntu-Regular, sans-serif;
        }

        /*---------------------------------------------*/
        a {
            font-family: Ubuntu-Regular;
            font-size: 14px;
            line-height: 1.7;
            color: #666666;
            margin: 0;
            transition: all 0.4s;
            -webkit-transition: all 0.4s;
            -o-transition: all 0.4s;
            -moz-transition: all 0.4s;
        }

        a:focus {
            outline: none !important;
        }

        a:hover {
            text-decoration: none;
            color: #7761d3;
        }

        /*---------------------------------------------*/
        h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }

        p {
            font-family: Ubuntu-Regular;
            font-size: 14px;
            line-height: 1.7;
            color: #a8a8a8;
            margin: 0;
        }

        ul, li {
            margin: 0;
            list-style-type: none;
        }


        /*---------------------------------------------*/
        input {
            outline: none;
            border: none;
        }

        textarea {
            outline: none;
            border: none;
        }

        textarea:focus, input:focus {
            border-color: transparent !important;
            border-radius: 0.5rem;
        }

        input::-webkit-input-placeholder {
            color: #a8a8a8;
        }

        input:-moz-placeholder {
            color: #a8a8a8;
        }

        input::-moz-placeholder {
            color: #a8a8a8;
        }

        input:-ms-input-placeholder {
            color: #a8a8a8;
        }

        textarea::-webkit-input-placeholder {
            color: #a8a8a8;
        }

        textarea:-moz-placeholder {
            color: #a8a8a8;
        }

        textarea::-moz-placeholder {
            color: #a8a8a8;
        }

        textarea:-ms-input-placeholder {
            color: #a8a8a8;
        }

        label {
            display: block;
            margin: 0;
        }

        /*---------------------------------------------*/
        button {
            outline: none !important;
            border: none;
            background: transparent;
        }

        button:hover {
            cursor: pointer;
        }

        iframe {
            border: none !important;
        }


        /*//////////////////////////////////////////////////////////////////
        [ Utility ]*/
        .txt1 {
            font-family: Ubuntu-Regular;
            font-size: 16px;
            color: #7761d3;
            line-height: 1.4;
        }


        /*//////////////////////////////////////////////////////////////////
        [ login ]*/

        .limiter {
            width: 100%;
            margin: 0 auto;
        }

        .container-login100 {
            width: 100%;
            min-height: 100vh;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            padding: 15px;

            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;;
        }


        .wrap-login100 {
            width: 390px;
            background: #232323;
            border-radius: 0.25rem;
            position: relative;
        }


        /*==================================================================
        [ Form ]*/

        .login100-form {
            width: 100%;
        }

        .login100-form-title {
            font-family: Ubuntu-Bold;
            font-size: 30px;
            color: #7761d3;
            line-height: 1.2;
            text-transform: uppercase;
            text-align: center;

            width: 100%;
            display: block;
        }


        /*------------------------------------------------------------------
        [ Input ]*/

        .wrap-input100 {
            width: 100%;
            position: relative;
            background-color: #1e1e1e;
            border: 1px solid transparent;
            border-radius: 0.5rem;
        }


        /*---------------------------------------------*/
        .input100 {
            font-family: Ubuntu-Bold;
            color: #7761d3;
            line-height: 1.2;
            font-size: 18px;

            display: block;
            width: 100%;
            background: transparent;
            height: 62px;
            padding: 0 20px 0 38px;
        }

        /*------------------------------------------------------------------
        [ Focus Input ]*/

        .focus-input100 {
            position: absolute;
            display: block;
            width: calc(100% + 2px);
            height: calc(100% + 2px);
            top: -1px;
            left: -1px;
            pointer-events: none;
            border: 1px solid #7761d3;
            border-radius: 3px;

            visibility: hidden;
            opacity: 0;

            -webkit-transition: all 0.4s;
            -o-transition: all 0.4s;
            -moz-transition: all 0.4s;
            transition: all 0.4s;

            -webkit-transform: scaleX(1.1) scaleY(1.3);
            -moz-transform: scaleX(1.1) scaleY(1.3);
            -ms-transform: scaleX(1.1) scaleY(1.3);
            -o-transform: scaleX(1.1) scaleY(1.3);
            transform: scaleX(1.1) scaleY(1.3);
        }

        .input100:focus + .focus-input100 {
            visibility: visible;
            opacity: 1;

            -webkit-transform: scale(1);
            -moz-transform: scale(1);
            -ms-transform: scale(1);
            -o-transform: scale(1);
            transform: scale(1);
        }

        .eff-focus-selection {
            visibility: visible;
            opacity: 1;

            -webkit-transform: scale(1);
            -moz-transform: scale(1);
            -ms-transform: scale(1);
            -o-transform: scale(1);
            transform: scale(1);
        }


        /*==================================================================
        [ Restyle Checkbox ]*/

        .input-checkbox100 {
            display: none;
        }

        .label-checkbox100 {
            font-family: Ubuntu-Regular;
            font-size: 16px;
            color: #999999;
            line-height: 1.2;

            display: block;
            position: relative;
            padding-left: 26px;
            cursor: pointer;
        }

        .label-checkbox100::before {
            content: "\f00c";
            font-family: FontAwesome;
            font-size: 13px;
            color: transparent;

            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 3px;
            background: #232323;
            border: 2px solid #7761d3;
            left: 0;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        .input-checkbox100:checked + .label-checkbox100::before {
            color: #7761d3;
        }


        /*------------------------------------------------------------------
        [ Button ]*/
        .container-login100-form-btn {
            width: 100%;
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
        }

        .login100-form-btn {
            font-family: Ubuntu-Bold;
            font-size: 16px;
            color: #fff;
            line-height: 1.2;
            text-transform: uppercase;

            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
            width: 100%;
            height: 62px;
            background-color: #827ffe;
            border-radius: 3px;

            -webkit-transition: all 0.4s;
            -o-transition: all 0.4s;
            -moz-transition: all 0.4s;
            transition: all 0.4s;
        }

        .login100-form-btn:hover {
            background-color: #6260d5;
            border-radius: 0.5rem;
        }


        /*------------------------------------------------------------------
        [ Alert validate ]*/

        .validate-input {
            position: relative;
        }


        /*//////////////////////////////////////////////////////////////////
        [ Responsive ]*/
    </style>
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

<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-t-50 p-b-90">
            <form class="login100-form validate-form flex-sb flex-w" method="post">
					<span class="login100-form-title p-b-51">
						Forgot
					</span>

                <div class="wrap-input100 validate-input m-b-16">
                    <input class="input100" type="email" name="email" placeholder="Email">
                    <span class="focus-input100"></span>
                </div>

                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                <div class="container-login100-form-btn m-t-17">
                    <button name="reset" class="login100-form-btn">
                        Reset Password
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
</body>

</html>