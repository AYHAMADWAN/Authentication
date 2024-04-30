<?php
session_start();

$context = stream_context_create([
    'http' => [
        'header' => 'Authorization: Bearer ' . $_SESSION['access_token']
    ]
]);
$url = 'https://graph.facebook.com/me?fields=id,name,email';
$response2 = file_get_contents($url, false, $context);
echo $response2;