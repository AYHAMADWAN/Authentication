<?php
session_start();

if(!isset($_SESSION['userinfo']) || empty($_SESSION['userinfo']) || ((!isset($_SESSION['2FA']) || $_SESSION['2FA'] != true) && $_SESSION['userinfo']['verified'] == 1)){
    header('Location: ./login.php');
    exit;
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
        if($_SESSION['userinfo']['verified'] != 1){
            echo 'Your email is unverified! You can only enable 2 Factor Authentication with a verified email<br>';
            echo '<a href="http://localhost/authentication/send-verification.php">Verify email</a>';
        }
        else{
            echo 'Your email is verified!';
        }

    ?>
</body>
</html>