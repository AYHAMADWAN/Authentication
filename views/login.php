
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
<link rel='stylesheet' href='./styles.css'>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <p><?= $_GET['error'] ?? null?></p>
        <form action="../process-login.php" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>
        <a href='./signup.php'>Create an account</a>
       
    </div>
</body>
</html>