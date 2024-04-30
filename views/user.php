<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../Database.php';


use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

if(!isset($_SESSION['userinfo']) || empty($_SESSION['userinfo']) || ((!isset($_SESSION['2FA']) || $_SESSION['2FA'] != true) && $_SESSION['userinfo']['verified'] == 1)){
    header('Location: ./login.php');
    exit;
}

if(isset($_SESSION['jwt'])){
    $jwt = $_SESSION['jwt'];
    $jwks = json_decode(file_get_contents('https://limited.facebook.com/.well-known/oauth/openid/jwks/'),true);
    // check token validity
    try{
        $decoded = JWT::decode($jwt, JWK::parseKeySet($jwks));
        }
        catch(ExpiredException $e){
            echo 'Token Expired';
            exit;
        }
        catch(SignatureInvalidException $e){
            echo 'Invalid Signature';
            exit;
        }
        catch(Exception $e){
            echo $e->getMessage();
            exit;
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
</head>
<body>
    <h1>You are a normal user</h1><br>
    <?php
        $verified = $_SESSION['userinfo']['verified'];
        if($verified != 1 && $verified != -1){
            echo 'Your email is unverified! You can only enable 2 Factor Authentication with a verified email<br>';
            echo '<a href="http://localhost/authentication/send-verification.php">Verify email</a>';
        }
        else if ($verified == -1){
            echo 'You are logged in with Facebook';
        }
        else{
            echo 'Your email is verified!';
        }
        echo '<br>uname: ' . $_SESSION['userinfo']['username'];

    ?>
    <br>
    <a href='http://localhost/authentication/logout.php'>Logout</a>
</body>
</html>