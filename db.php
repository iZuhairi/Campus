<?php
$conn = new mysqli("localhost","root","","campus_service_hub");
if($conn->connect_error){
    die("DB Error");
}
?>