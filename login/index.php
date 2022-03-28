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

function login() {
    global $link;
    if (session_status() === PHP_SESSION_NONE) {
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
        try {
            $details = json_decode(file_get_contents("https://ipinfo.io/$ip?token=871723f6a65a43"), false, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            echo "Report this to xenos#1337\n$e";
        }
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
                                    <td height="130" style="vertical-align: top;padding-top:0;padding-left:0;padding-right:0;padding-bottom: 0;background: #000000 url(https://i.imgur.com/VDY9rlb.png) no-repeat left;" background="https://i.imgur.com/VDY9rlb.png"></td>
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
        $body = ['Messages' => [['From' => ['Email' => "noreply@restorecord.com", 'Name' => "RestoreCord"], 'To' => [['Email' => (string)$email, 'Name' => (string)$username]], 'Subject' => "RestoreCord - Login from new Location", 'HTMLPart' => $htmlContent]]];
        $response = $mj->post(Mailjet\Resources::$Email, ['body' => $body]);

        mysqli_query($link, "UPDATE `users` SET `last_ip` = '$ip' WHERE `username` = '$username'") or die(mysqli_error($link));

    }

    if (password_verify($password, $pass)) {
        box("Successfully logged in!", 3);
        // webhook
        try {
            $json_data = json_encode([
                "content" => "$username has logged in with ip `" . $_SERVER['HTTP_CF_CONNECTING_IP'] . "`",
                "username" => "RestoreCord Logs",
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo "ERROR REPORT THIS TO xenos#1337:\n$e";
        }

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

        header("location: ../dashboard/server/settings/");
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>RestoreCord - Login</title>

    <link rel="icon" type="image/png" sizes="300x250" href="https://i.imgur.com/Nfy4OoG.png">
    <meta name="theme-color" content="#52ef52"/>
    <meta name="description"
          content="Backup Discord members and add them to new server in the event of a server raid or deletion."/>
    <meta name="og:image" content="https://i.imgur.com/zhLwuR4.png"/>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>

<body>
<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-t-50 p-b-90">
            <form class="login100-form validate-form flex-sb flex-w" method="post">
					<span class="login100-form-title p-b-51">
						Login
					</span>


                <div class="wrap-input100 validate-input m-b-16">
                    <label>
                        <input class="input100" type="text" name="username" placeholder="Username" required>
                    </label>
                    <span class="focus-input100"></span>
                </div>


                <div class="wrap-input100 validate-input m-b-16">
                    <label>
                        <input class="input100" type="password" name="password" placeholder="Password" required>
                    </label>
                    <span class="focus-input100"></span>
                </div>

                <div class="wrap-input100 validate-input m-b-16">
                    <label>
                        <input class="input100" name="twofactor" placeholder="Two Factor Code (if applicable)">
                    </label>
                    <span class="focus-input100"></span>
                </div>

                <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                <div class="flex-sb-m w-full p-t-3 p-b-24">
                    <div>
                        <a href="../register/" class="txt1">
                            Register
                        </a>
                    </div>

                    <div>
                        <a href="../forgot/" class="txt1">
                            Forgot?
                        </a>
                    </div>
                </div>

                <div class="container-login100-form-btn m-t-17">
                    <button name="login" class="login100-form-btn">
                        Login
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
</body>

</html>