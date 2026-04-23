<?php
session_start();
include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#6c5ce7,#a29bfe);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family: Arial;
}

.login-box{
    width:380px;
    background:white;
    padding:35px;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

.login-box h2{
    font-weight:bold;
    text-align:center;
    margin-bottom:20px;
}

.form-control{
    padding:12px;
    border-radius:10px;
}

.btn-login{
    background:linear-gradient(135deg,#6c5ce7,#a29bfe);
    color:white;
    border:none;
    width:100%;
    padding:12px;
    border-radius:10px;
    font-weight:bold;
}

.btn-register{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:2px solid #6c5ce7;
    color:#6c5ce7;
    text-align:center;
    display:block;
    text-decoration:none;
}

.error{
    color:red;
    text-align:center;
    margin-top:10px;
}
</style>
</head>

<body>

<div class="login-box">

<h2>Login</h2>

<form method="POST">

    <input class="form-control mb-3" name="email" placeholder="Email">

    <input class="form-control mb-3" type="password" name="password" placeholder="Password">

    <!-- COOKIE OPTION -->
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember">
        <label class="form-check-label">Remember Me</label>
    </div>

    <button class="btn-login" name="login">Login</button>

</form>

<?php
if(isset($_POST['login'])){

    $email = $_POST['email'];
    $pass = $_POST['password'];

    $res = $conn->prepare("SELECT * FROM users WHERE email=?");
    $res->bind_param("s", $email);
    $res->execute();
    $result = $res->get_result();
    $user = $result->fetch_assoc();

    if($user && password_verify($pass, $user['password'])){

        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // =========================
        // COOKIE FEATURE
        // =========================
        if(isset($_POST['remember'])){

            setcookie("user_email", $email, time() + (86400 * 7), "/"); 
            setcookie("user_pass", $pass, time() + (86400 * 7), "/");
        }

        header("Location: dashboard.php");
        exit();

    } else {
        echo "<div class='error'> Wrong email or password</div>";
    }
}
?>

<hr>

<p class="text-center text-muted">Don’t have an account?</p>

<a href="register.php" class="btn-register">
Create New Account
</a>

</div>

</body>
</html>