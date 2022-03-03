<?php

use Mailjet\Client;

include '../includes/connection.php';
include '../includes/functions.php';
session_start();

if (isset($_SESSION['username'])) {
    header("Location: ../dashboard/server/settings/");
    exit();
}

if (isset($_POST['reset'])) {
    reset();
}

function reset()
{
    global $link;
    $recaptcha_response = sanitize($_POST['recaptcha_response']);
    $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6LczE5wcAAAAAPaxp7B95p3NWrzMxA4A_0HoB4BE&response=' . $recaptcha_response);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/auth/css/util.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/auth/css/main.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6LczE5wcAAAAAHXJy3TX_KzaK45ZvegzqcAeoJ-i"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6LczE5wcAAAAAHXJy3TX_KzaK45ZvegzqcAeoJ-i', {
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