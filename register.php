<?php
include "db.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

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

.register-box{
    width:400px;
    background:white;
    padding:35px;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

.register-box h2{
    text-align:center;
    font-weight:bold;
    margin-bottom:20px;
}

.form-control{
    padding:12px;
    border-radius:10px;
}

.btn-register{
    background:linear-gradient(135deg,#00b894,#55efc4);
    color:white;
    border:none;
    width:100%;
    padding:12px;
    border-radius:10px;
    font-weight:bold;
    transition:0.3s;
}

.btn-register:hover{
    transform:scale(1.03);
}

.login-link{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#6c5ce7;
    text-decoration:none;
    font-weight:bold;
}

.login-link:hover{
    text-decoration:underline;
}

.success{
    color:green;
    text-align:center;
    margin-top:10px;
}

.error{
    color:red;
    text-align:center;
    margin-top:10px;
}
</style>
</head>

<body>

<div class="register-box">

<h2>📝 Register</h2>

<form method="POST">

    <input class="form-control mb-3" name="name" placeholder="Full Name" required>

    <input class="form-control mb-3" name="email" placeholder="Email Address" required>

    <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>

    <button class="btn-register" name="reg">
        Create Account
    </button>

</form>

<?php
if(isset($_POST['reg'])){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // =========================
    // CHECK DUPLICATE EMAIL
    // =========================
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if($result->num_rows > 0){

        echo "<div class='error'> Email already exists!</div>";

    } else {

        // INSERT USER
        $stmt = $conn->prepare("
            INSERT INTO users(name,email,password)
            VALUES (?,?,?)
        ");
        $stmt->bind_param("sss", $name, $email, $pass);

        if($stmt->execute()){
            echo "<div class='success'>✅ Account created successfully</div>";

                    echo "<script>
                    setTimeout(() => {
                        window.location.replace('login.php');
                    }, 1200);
                    </script>";
        } else {
            echo "<div class='error'> Failed to register</div>";
        }
    }
}
?>

                <a href="login.php" class="login-link">
                    Back to Login
                </a>

</div>

</body>
</html>