<?php

include __DIR__ . '/Database.php';

if(!isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password']) ||
 !isset($_POST['confirm_password']) || empty($_POST['confirm_password']) || !isset($_POST['email']) || empty($_POST['email'])){
    header('Location: ./views/signup.php?error=fill+in+everything');
    exit;
}

// connect to database
$database = new Database('localhost', 'authdb', 'root', '');
if(!$database->connect()){
    exit;
}

// make sure username isn't taken
$sql = 'SELECT * FROM users WHERE username=:username';
$params = [':username'=>$_POST['username']];
$username_result = Database::query($sql, $params);

if(!empty($username_result)){
    header('Location: ./views/signup.php?error=username+already+in+use');
    exit;
}

// make sure email isn't taken
$sql = 'SELECT * FROM users WHERE email=:email';
$params = [':email'=>$_POST['email']];
$email_result = Database::query($sql, $params);

if(!empty($email_result)){
    header('Location: ./views/signup.php?error=email+already+in+use');
    exit;
}

// make sure passwords match
if($_POST['password'] != $_POST['confirm_password']){
    header('Location: ./views/signup.php?error=passwords+don\'t+match');
    exit;
}

$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// insert the values into the database
$sql = 'INSERT INTO users(username, password, email) VALUES(:username, :password, :email)';
$params = [':username'=>$_POST['username'], ':email'=>$_POST['email'], ':password'=>$hashed_password];
Database::query($sql, $params);
header('Location: ./views/login.php');


// close database connection
$database->close();