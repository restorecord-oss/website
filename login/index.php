<?php
include '../includes/connection.php';
include '../includes/functions.php';



if (isset($_SESSION['username']))
{
    header("Location: ../dashboard/server/settings/");
    exit();
}

if (isset($_POST['login']))
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
	}

    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    ($result = mysqli_query($link, "SELECT * FROM `users` WHERE `username` = '$username'")) or die(mysqli_error($link));

    if (mysqli_num_rows($result) == 0)
    {
        error("Account doesn\'t exist!");
        return;
    }
    while ($row = mysqli_fetch_array($result))
    {
        $pass = $row['password'];
        $email = $row['email'];
        $role = $row['role'];
        $banned = $row['banned'];
		$last_ip = $row['last_ip'];
	
        $twofactor_optional = $row['twofactor'];
        $google_Code = $row['googleAuthCode'];
    }

    if (!is_null($banned))
    {
        error("Banned: Reason: " . sanitize($banned));
        return;
    }

    if (!password_verify($password, $pass))
    {
        error("Password is invalid!");
        return;
    }

    if ($twofactor_optional)
    {
        $twofactor = sanitize($_POST['twofactor']);
        if (empty($twofactor))
        {
            error("Two factor field needed for this acccount!");
            return;
        }
	
        require_once '../auth/GoogleAuthenticator.php';
        $gauth = new GoogleAuthenticator();
        $checkResult = $gauth->verifyCode($google_Code, $twofactor, 2);
	
        if (!$checkResult)
        {
            error("2FA code Invalid!");
            return;
        }
    }

	$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];

	if ($last_ip == NULL) {
		mysqli_query($link, "UPDATE `users` SET `last_ip` = '$ip' WHERE `username` = '$username'") or die(mysqli_error($link));
	} else {
		if ($last_ip != $ip) {
			mysqli_query($link, "UPDATE `users` SET `last_ip` = '$ip' WHERE `username` = '$username'") or die(mysqli_error($link));
			$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}?token=871723f6a65a43"));
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
			                            <td height="130" style="background-repeat:no-repeat;vertical-align: top;background-size:cover;background: #000000 url(https://i.imgur.com/VDY9rlb.png) no-repeat left/cover;padding-top:0;padding-left:0;padding-right:0;padding-bottom: 0px;background-position:left;" background="https://i.imgur.com/VDY9rlb.png"></td>
			                        </tr>
			                    </tbody>
			                </table>
			            </div>
			            <div style="margin:0px auto;max-width:570px;background:#ffffff">
			                <table role="presentation" cellpadding="0" cellspacing="0"
			                    style="font-size:0px;width:100%;background:#ffffff">
			                    <tbody>
			                        <tr>
			                            <td
			                                style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px">
			                                <div
			                                    style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
			                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
			                                        <tbody>
			                                            <tr>
			                                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px">
			                                                    <div
			                                                        style="color:#000000;font-family:Roboto,sans-serif;font-size:14px;font-weight:400;line-height:1.5;text-align:left">
			                                                        <br>Hey ' . $username . ',<br>
			                                                    </div>
			                                                </td>
			                                            </tr>
			                                            <tr>
			                                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px">
			                                                    <div style="color:#000000;font-family:Roboto,sans-serif;font-size:14px;font-weight:400;line-height:1.5;text-align:left">
																	Successful Login from a new location. If this was you, you can safely ignore this message. If this was <strong>not</strong> you, Contact support immediately, and change your password.<br><br>
			                                                        <strong>IP Address:</strong> ' . $details->ip . '<br>
			                                                        <strong>Location:</strong> ' . $details->city . ', ' . $details->region . ', ' . $details->country . '<br><br>
			                                                    </div>
			                                                </td>
			                                            </tr>
			                                            <tr>
			                                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px">
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
			            <div style="margin:0px auto;max-width:570px;background:#f9f7ff">
			                <table role="presentation" cellpadding="0" cellspacing="0"
			                    style="font-size:0px;width:100%;background:#f9f7ff">
			                    <tbody>
			                        <tr>
			                            <td
			                                style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px">
			                                <div class="m_162601587205469835mj-column-per-100"
			                                    style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%">
			                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
			                                        <tbody>
			                                            <tr>
			                                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px"></td>
			                                            </tr>
			                                            <tr>
			                                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px">
			                                                    <div
			                                                        style="color:#8f8e93;font-family:Roboto,sans-serif;font-size:12px;font-weight:400;line-height:1.5;text-align:left">
			                                                        Use of this Website constitutes acceptance of the
			                                                        <a href="https://restorecord.com/terms/"
			                                                            style="color: #7388db;text-decoration:none;"
			                                                            target="_blank">Terms of Service and Privacy policy.</a> All copyrights, trade
			                                                        marks, service
			                                                        marks belong to the corresponding owners.</div>
			                                                </td>
			                                            </tr>
			                                            <tr>
			                                                <td style="word-wrap:break-word;font-size:0px;padding:10px 25px">
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

			$mj = new \Mailjet\Client("2a035b8a2efdab6216da2129c8a637e3", "64134d8f0ba141259f3ca87812c60490",true,['version' => 'v3.1']);
			$body = [
				'Messages' => [
					[
						'From' => [
							'Email' => "noreply@restorecord.com",
							'Name' => "RestoreCord"
						],
						'To' => [
							[
								'Email' => "$email",
								'Name' => "$username"
							]
						],
						'Subject' => "RestoreCord - Login from new Location",
						'HTMLPart' => $htmlContent
					]
				]
			];
			$response = $mj->post(Mailjet\Resources::$Email, ['body' => $body]);

			mysqli_query($link, "UPDATE `users` SET `last_ip` = '$ip' WHERE `username` = '$username'") or die(mysqli_error($link));

		}
	}

	// webhook start
	$timestamp = date("c", strtotime("now"));
	
	$json_data = json_encode([
	// Message
	"content" => "{$username} has logged in with ip `" . $_SERVER['HTTP_CF_CONNECTING_IP'] . "`",
	
	// Username
	"username" => "RestoreCord Logs",
	
	], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	
	$ch = curl_init("https://discord.com/api/webhooks/901571010845872189/5kkbnUx0oFEocn2pHe8otDfmDGxD09DCZshICTF56DJRf622Dg8E-HHF45asci17WcV5");
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

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>RestoreCord - Login</title>

	<link rel="icon" type="image/png" sizes="300x250" href="https://i.imgur.com/Nfy4OoG.png">
	<meta name="theme-color" content="#52ef52" />
	<meta name="description"
		content="Backup Discord members and add them to new server in the event of a server raid or deletion." />
	<meta name="og:image" content="https://i.imgur.com/zhLwuR4.png" />

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/auth/css/util.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.restorecord.com/auth/css/main.css">
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
						<input class="input100" type="text" name="username" placeholder="Username" required>
						<span class="focus-input100"></span>
					</div>


					<div class="wrap-input100 validate-input m-b-16">
						<input class="input100" type="password" name="password" placeholder="Password" required>
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-16">
						<input class="input100" name="twofactor" placeholder="Two Factor Code (if applicable)">
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

	<script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
</body>

</html>