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
    <title>Admin Page</title>
</head>
<body>
    you are an admin<br>

    <?php
    use PragmaRX\Google2FAQRCode\Google2FA;

    if(!$_SESSION['userinfo']['authenticator_key']){
        require __DIR__ . '/../vendor/autoload.php';
        require __DIR__ . '/../Database.php';

        $google2fa = new Google2FA();
        $userKey = $google2fa->generateSecretKey();

        $inlineUrl = $google2fa->getQRCodeInline(
            'pragmarx',
            'google2fa@pragmarx.com',
            $userKey
        );

        // connect to DB
        $database = new Database('localhost', 'authdb', 'root', '');
        if(!$database->connect()){
            exit;
        }

        // make the query
        $sql = 'UPDATE users SET authenticator_key=:code WHERE username=:username';
        $params = [':code'=>$userKey, ':username'=>$_SESSION['userinfo']['username']];
        $result = Database::query($sql, $params);

        $_SESSION['userinfo']['authenticator_key'] = $userKey;

        echo 'scan this QR code with authenticator app<br>';
        echo $inlineUrl;
    }
    ?>
</body>
</html>