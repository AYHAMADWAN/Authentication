<?php
session_start();

if(!isset($_SESSION['userinfo']) || empty($_SESSION['userinfo']) || $_SESSION['userinfo']['roleid'] != 2){
    echo 'you are unauthorized to use the admin page';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    you are an admin
</body>
</html>