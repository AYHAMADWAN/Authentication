<?php

session_start();
include_once __DIR__ .'/Database.php';

if(!isset($_SESSION['userinfo']) || empty($_SESSION['userinfo'])){
    header('Location: ./login.php');
    exit;
}

// connect to database
$database = new Database('localhost', 'authdb', 'root', '');
if(!$database->connect()){
    exit;
}

// get email
$email = $_SESSION['userinfo']['email'];
$username = $_SESSION['userinfo']['username'];


// generate token
$token = bin2hex(random_bytes(16));

// API endpoint URL
$url = "https://api.mailersend.com/v1/email";

// Request body
$data = array(
    "from" => array(
        "email" => "MS_oIH2O5@trial-0p7kx4xqow7g9yjr.mlsender.net",
        "name" => "App"
    ),
    "to" => array(
        array(
            "email" => $email,
            "name" => $username
        )
    ),
    "subject" => "Verify Your Email",
    "text" => "",
    "html" => "<a href='http://localhost/authentication/views/verify-email.php?token=${token}'>Click Here</a> To Verify Your Email", // BLANK :::::: ADD LOCATION OF TOKEN PROCESSOR
);

// Convert data to JSON format
$data_json = json_encode($data);

// Authorization token
$authorization = "Bearer mlsn.9edac534e24ab5994def44b9c818fe7f74842f56a918de72535a4fa8be7400e8";

// Set up cURL
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: ' . $authorization
));

// Execute cURL request
$response = curl_exec($curl);

// Check for errors
if ($response === false) {
    $error = curl_error($curl);
    echo "Error: " . $error;
} else {
    curl_close($curl);

    // delete any existing token for the user
    $sql = 'DELETE FROM verify WHERE username= :username';
    $params = [':username'=>$username];
    Database::query($sql, $params);

    // make token valid for 30mins
    $expiry = date("Y-m-d H:i:s",time() + 60 * 30);

    // insert the code into the database
    $sql = 'INSERT INTO verify VALUES(:username, :token, :expire)';
    $params = [':username'=>$username, ':token'=>$token, ':expire'=>$expiry];
    Database::query($sql, $params);

    header('Location: ./views/verify-email.php');
}







// mlsn.9edac534e24ab5994def44b9c818fe7f74842f56a918de72535a4fa8be7400e8