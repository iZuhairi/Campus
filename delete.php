<?php
session_start();
include "db.php";

$id = $_GET['id'];
$user_id = $_SESSION['id'];
$role = $_SESSION['role'];

// check owner or admin
$stmt = $conn->prepare("SELECT user_id FROM services WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if($data && ($data['user_id'] == $user_id || $role == 'admin')){
    
    $stmt = $conn->prepare("DELETE FROM services WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();

}

header("Location: dashboard.php");