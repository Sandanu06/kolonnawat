<?php
session_start();
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$login_error = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $con = new mysqli('127.0.0.1','root','', 'kolonnawatravels');
    if ($con->connect_error) {
        $login_error = 'DB connect error: ' . $con->connect_error;
    } else {
        $stmt = $con->prepare('SELECT * FROM admin WHERE username = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $rec = $res->fetch_assoc();
        if (!$rec) {
            $login_error = 'Invalid Entry - No Member Found';
        } else {
            $dbpass = $rec['password'] ?? $rec['pass'] ?? '';
            $ok = false;
            if ($dbpass !== '' && password_verify($password, $dbpass)) {
                $ok = true;
            }
            if (!$ok && $password === $dbpass) {
                $ok = true; // legacy plaintext match
            }

            if ($ok) {
                // successful login
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $rec['username'];
                // regenerate session id
                session_regenerate_id(true);
                header('Location: registeradminpannel.php');
                exit();
            } else {
                $login_error = 'Incorrect email or password';
            }
        }
        $stmt->close();
        $con->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Kolonnawa Travels</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
        .login-container { width:350px; margin:80px auto; background:#fff; padding:30px; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        h2 { text-align:center; }
        input[type=text], input[type=password] { width:100%; padding:10px; margin:10px 0; }
        input[type=submit] { width:100%; padding:10px; background:#333; color:#fff; border:none; border-radius:5px; cursor:pointer; }
        input[type=submit]:hover { background:#007bff; }
        .error { background:#ffecec; padding:8px; border:1px solid #f5c6cb; color:#7a1620; border-radius:4px; margin-bottom:10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🔒 Admin Login</h2>
        <?php if ($login_error) echo '<div class="error">' . htmlspecialchars($login_error) . '</div>'; ?>
        <form method="post" autocomplete="off">
            <input type="text" name="username" placeholder="Username (email)" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
        </form>
    </div>
</body>
</html>
