<?php
session_start();
include_once __DIR__ .'/Database.php';

// make sure everything is filled in
if(!isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password'])){
    header('Location: ./views/login.php?error=fill+in+everything');
    exit;
}

// connect to database
$database = new Database('localhost', 'authdb', 'root', '');
if(!$database->connect()){
    exit;
}

// hash the given password
$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// make the query
$sql = 'SELECT * FROM users WHERE username=:username';
$params = [':username'=>$_POST['username']];
$result = Database::query($sql, $params);

// if user not found
if(empty($result)){
    header('Location: ./views/login.php?error=wrong+username+or+password');
    exit;
}

// if wrong password
if(!password_verify($_POST['password'], $result[0]['password'])){
    header("Location: ./views/login.php?error=wrong+username+or+passwor");
    exit;
}

$_SESSION['userinfo'] = $result[0];
$_SESSION['2FA'] = false;

// view page based on role
if ($_SESSION['userinfo']['roleid'] == 1){
    if ($_SESSION['userinfo']['verified'] != 1){
        header('Location: ./views/user.php');
    }
    else{
        header('Location: ./send-2fa-code.php');
    }
    exit;
}
else if($_SESSION['userinfo']['roleid'] == 2){
    if(isset($_SESSION['userinfo']['authenticator_key'])){
        header('Location: ./views/enter-code.php');
        exit;
    }else{
        header('Location: ./views/admin.php');
    }
    exit;
}

// close database connection
$database->close();