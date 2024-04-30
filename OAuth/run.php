<?php
session_start();

$_SESSION['state'] = bin2hex(random_bytes(16));
header("Location: http://www.facebook.com/v19.0/dialog/oauth?response_type=code&state={$_SESSION['state']}&client_id=3719837408295631&scope=openid&redirect_uri=http://localhost/authentication/OAuth/redirect.php");

// &code_challenge=8UBHZD3GjDLZDgx5WuxgPCpmJ4R4JTBxgSph29CRU3o&nonce=123