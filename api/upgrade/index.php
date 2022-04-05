<?php
include '../../includes/connection.php';

if (isset($_SERVER['HTTP_X_SELLIX_SIGNATURE'])) {

    $payload = file_get_contents('php://input');

    $secret = $sellix_secret;
    $header_signature = $_SERVER["HTTP_X_SELLIX_SIGNATURE"];
    $signature = hash_hmac('sha512', $payload, $secret);
    if (hash_equals($signature, $header_signature)) {

        $json = json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
        if ($json->event == 'product:dynamic') {

            $un = $json->data->custom_fields->username;

            switch ($json->data->product_title) {
                case "RestoreCord Premium":
                    $expires = time() + 31556926;
                    mysqli_query($link, "UPDATE `users` SET `role` = 'premium',`expiry` = '$expires' WHERE `username` = '$un'");
                    echo "Your Account has been upgraded to " . $json->data->product_title;
                    break;
                case "RestoreCord Business":
                    $expires = time() + 31556926;
                    mysqli_query($link, "UPDATE `users` SET `role` = 'business',`expiry` = '$expires' WHERE `username` = '$un'");
                    echo "Your Account has been upgraded to " . $json->data->product_title;
                    break;
                case "RestoreCord Premium Monthly":
                    $expires = time() + 2629743;
                    mysqli_query($link, "UPDATE `users` SET `role` = 'premium',`expiry` = '$expires' WHERE `username` = '$un'");
                    echo "Your Account has been upgraded to " . $json->data->product_title;
                    break;
                case "RestoreCord Business Monthly":
                    $expires = time() + 2629743;
                    mysqli_query($link, "UPDATE `users` SET `role` = 'business',`expiry` = '$expires' WHERE `username` = '$un'");
                    echo "Your Account has been upgraded to " . $json->data->product_title;
                    break;
                default:
                    echo "Product does not exist contact support.";
                    break;
            }
        } else {
            echo "Transaction failed (not paid) contact support.";
        }

    }
} else {
    echo "Invalid Signature";
}
