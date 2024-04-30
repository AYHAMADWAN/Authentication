<?php

session_start();
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/Database.php';

use PragmaRX\Google2FA\Google2FA;

// connect to database
$database = new Database('localhost', 'authdb', 'root', '');
if(!$database->connect()){
    exit;
}

// make the query
$sql = 'SELECT authenticator_key FROM users WHERE username=:username';
$params = [':username'=>$_SESSION['userinfo']['username']];
$result = Database::query($sql, $params);

$google2fa = new Google2FA();
$userKey = $result[0]['authenticator_key'];


$window = 1;
$valid = $google2fa->verifyKey($userKey, $_GET['code'], $window);

// var_dump($google2fa->getCurrentOtp($userKey));

if($valid){
    $_SESSION['authenticator_code'] = true;
    header('Location: ./views/admin.php');
}
else{
    echo 'not valid';
}
