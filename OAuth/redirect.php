<?php
session_start();

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/../Database.php';


use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;


// check state
if ($_GET['state'] != $_SESSION['state']){
    echo 'invalid state';
    exit;
}

// send request to get token
$response = json_decode(file_get_contents("https://graph.facebook.com/v19.0/oauth/access_token?client_secret=7826359d0499af31da4d38718afe21d0&client_id=3719837408295631&redirect_uri=http://localhost/authentication/OAuth/redirect.php&code={$_GET['code']}"), true);
$jwt = $response['id_token'];



// Validate the structure and signature of the JWT

$validate_signature = verify_jwt_signature($jwt);
if (validate_jwt_structure($jwt) && $validate_signature[0]) {
    $data = json_decode(json_encode($validate_signature[1]), true);

    $username = $data['name'];
    $email = $data ['email'];
    

    // **************** ADD USER TO DATABASE *******************
    // connect to database
    $database = new Database('localhost', 'authdb', 'root', '');
    if(!$database->connect()){
        exit;
    }

    // make sure username isn't taken
    $sql = 'SELECT * FROM users WHERE username=:username';
    $params = [':username'=>$username];
    $username_result = Database::query($sql, $params);

    if(empty($username_result)){
        $sql = 'INSERT INTO users(username, email, password) VALUES(:username, :email, :token)';
        $params = [':username'=>$username, ':email'=>$email, ':token'=>$jwt];
        Database::query($sql, $params);   
    }
    


    // redirect to user page
    session_start();
    $_SESSION['jwt'] = $jwt;
    $_SESSION['userinfo'] = ['username'=>$username, 'email'=>$email, 'verified'=>-1];
    header('Location: ../views/user.php');
    
    // echo '<form id="myForm" action="http://localhost/Authentication/process-signup.php" method="post">';
    // echo "<input type='hidden' name='username' value='{$username}'>";
    // echo "<input type='hidden' name='email' value='{$email}'>";
    // echo "<input type='hidden' name='jwt' value='true'>";
    // echo '</form>';
    // echo "<script>document.getElementById('myForm').submit();</script>";
}
exit;




function validate_jwt_structure($jwt) {
    // Split the JWT into its components
    $jwt_parts = explode('.', $jwt);

    // Ensure that the JWT has three parts
    if (count($jwt_parts) !== 3) {
        return false;
    }

    
    // Decode the JSON payload
    $payload = base64_decode(str_replace(['-', '_'], ['+', '/'], $jwt_parts[1]));

    // Decode the JSON payload into an associative array
    $payload_data = json_decode($payload, true);

    // Validate the structure of the decoded payload
    if (!$payload_data || !is_array($payload_data)) {
        return false;
    }

    // Check if the payload contains the expected claims
    if (!isset($payload_data['iss']) || !isset($payload_data['exp']) || !isset($payload_data['sub'])) {
        return false;
    }

    return true;
}


function verify_jwt_signature($jwt){
    // get public key
    $jwks = json_decode(file_get_contents('https://limited.facebook.com/.well-known/oauth/openid/jwks/'),true);
    $result = true;
    try{
        $decoded = JWT::decode($jwt, JWK::parseKeySet($jwks));
        }
        catch(ExpiredException $e){
            echo 'Token Expired';
            $result = false;
        }
        catch(SignatureInvalidException $e){
            echo 'Invalid Signature';
            $result = false;
        }
        catch(Exception $e){
            echo $e->getMessage();
            $result = false;
        }
    return array($result, $decoded ?? null);
}

// client_secret=7826359d0499af31da4d38718afe21d0
// &code_verifier=8UBHZD3GjDLZDgx5WuxgPCpmJ4R4JTBxgSph29CRU3o
?>


