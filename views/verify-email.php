<?php
session_start();
include_once '../Database.php';

$message = 'verification link has been sent to your email';

if(isset($_GET['token'])){
    
    // connect to database
    $database = new Database('localhost', 'authdb', 'root', '');
    if(!$database->connect()){
        exit;
    }

    $sql = 'SELECT * FROM verify WHERE username=:username';
    $params = [':username'=>$_SESSION['userinfo']['username']];
    $result = Database::query($sql, $params);

    if(empty($result)){
        echo 'username not found!';
        exit;
    }

    // check expiration 
    $expiryDate = DateTime::createFromFormat("Y-m-d H:i:s", $result[0]['expiration']);
    $current = new DateTime();

    if($expiryDate < $current){
        echo "token expired";
        // delete the expired token
        $deleteRow = "DELETE FROM verify WHERE username=:username";
        $params = [':username'=>$result[0]['username']];
        Database::query($deleteRow, $params);
        exit;
    }

    if($_GET['token'] == $result[0]['token']){
        // delete token
        $sql = 'DELETE FROM verify WHERE username= :username';
        $params = [':username'=>$_SESSION['userinfo']['username']];
        Database::query($sql, $params);

        // change status to verified
        $sql = 'UPDATE users SET verified=1 WHERE username= :username';
        $params = [':username'=>$_SESSION['userinfo']['username']];
        Database::query($sql, $params);

        $_SESSION['userinfo']['verified'] = 1;

        $message = 'Your email has been verified!';
    }
    else{
        echo 'invalid token';
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
</head>
<body>
    <div class="container">
        <form>
            <?= $message ?>
        </form>
    </div>
    <a href="http://localhost/authentication/views/user.php">return to user page</a>
</body>
</html>