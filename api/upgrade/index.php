<?php
include '../../includes/connection.php';

if (isset($_SERVER['HTTP_X_SHOPPY_SIGNATURE'])) {

    $payload = file_get_contents('php://input');

    $secret = "JFQNRShog9Du30rE"; // replace with your webhook secret
    $header_signature = $_SERVER["HTTP_X_SHOPPY_SIGNATURE"]; // get our signature header

    $signature = hash_hmac('sha512', $payload, $secret);

    if (hash_equals($signature, $header_signature)) {

        $json = json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
        // in terms of looking at shoppy API, $json = $payload
        if ($json->event === 'order:paid') {
            $data = $json->data;
            $order = $data->order;
            // $product = $order->product;

            $product = $json->data->order->product;
            $un = $json->data->order->custom_fields[0]->value;


            $json_data = json_encode([
                'content' => NULL,
                'embeds' => [
                    0 => [
                        'title' => 'Order Completed!',
                        'description' => $json->data->order->id,
                        'color' => 5814783,
                        'fields' => [
                            0 => [
                                'name' => '**Product**',
                                'value' => $json->data->order->product->title,
                                'inline' => true,
                            ],
                            1 => [
                                'name' => '**Payment Method**',
                                'value' => $json->data->order->gateway,
                                'inline' => true,
                            ],
                            2 => [
                                'name' => '**Value**',
                                'value' => $json->data->order->price,
                                'inline' => true,
                            ],
                            3 => [
                                'name' => '**Email**',
                                'value' => $json->data->order->email,
                                'inline' => true,
                            ],
                            4 => [
                                'name' => '**IP Address**',
                                'value' => ":flag_" . strtolower($json->data->order->agent->geo->iso_code) . ": " . $json->data->order->agent->geo->ip,
                                'inline' => true,
                            ],
                            5 => [
                                'name' => '**Platform**',
                                'value' => $json->data->order->agent->data->platform . " @ " . $json->data->order->agent->data->browser->name . " " . $json->data->order->agent->data->browser->version,
                                'inline' => true,
                            ],
                            6 => [
                                'name' => '**Username**',
                                'value' => $un,
                                'inline' => true,
                            ],
                        ],
                    ],
                ],
                'username' => 'RestoreCord Orders',
                'avatar_url' => 'https://media.discordapp.net/attachments/923305361254010970/956266029439418459/52a91ac576917f41fe99b096a2c175ce.png',
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

            $headers = array(
                "Content-Type: application/json",
            );

            $url = "https://discord.com/api/webhooks/945922811325730868/piCkIZXGdrU8CM7PjyJHhPkZzBvSI3pHrR3pb602tar8mVIdqY0YdG_CtHewPqecvl7v";

            switch ($product->title) {
                case "RestoreCord Premium":
                    $expires = time() + 31556926;
                    mysqli_query($link, "UPDATE `users` SET `role` = 'premium',`expiry` = '$expires' WHERE `username` = '$un'");
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_exec($ch);
                    curl_close($ch);
                    die("upgraded to premium");
                case "RestoreCord Business":
                    $expires = time() + 31556926;
                    mysqli_query($link, "UPDATE `users` SET `role` = 'business',`expiry` = '$expires' WHERE `username` = '$un'");
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
                    curl_exec($ch);
                    curl_close($ch);
                    die("upgraded to business");
                default:
                    die("invalid product");
            }
        } else {
            die("didn't pay");
        }

    }
}

die("You shouldn't be here");

?>