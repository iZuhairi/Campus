<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];
$role = $_SESSION['role'] ?? 'user';
?>

<!DOCTYPE html>
<html>
<head>
<title>Campus Service Hub</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    background:#f5f7fb;
    font-family: Arial;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    height:100vh;
    position:fixed;
    top:0;
    left:0;
    background:linear-gradient(180deg,#1f1c2c,#928dab);
    color:white;
    padding:20px;
}

.sidebar h4{
    font-weight:bold;
    margin-bottom:20px;
}

.sidebar a{
    display:block;
    color:white;
    padding:12px;
    text-decoration:none;
    border-radius:10px;
    margin-bottom:8px;
    transition:0.3s;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.2);
    transform:translateX(5px);
}

/* CONTENT */
.content{
    margin-left:260px;
    padding:25px;
}

/* CARD */
.card{
    border:none;
    border-radius:15px;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

/* BUTTON */
.btn-primary{
    background:#6c5ce7;
    border:none;
    border-radius:10px;
}

.btn-primary:hover{
    background:#5a4bd1;
}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">

    <h4>Campus Hub</h4>

    <a href="dashboard.php">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="dashboard.php">
        <i class="bi bi-plus-circle"></i> Add Service
    </a>

    <!-- OPTIONAL: ROLE CONTROL -->
    <?php if($role == 'admin'){ ?>
        <a href="admin.php">
            <i class="bi bi-shield-lock"></i> Admin Panel
        </a>
    <?php } ?>

    <a href="logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>

</div>

<!-- CONTENT -->
<div class="content">