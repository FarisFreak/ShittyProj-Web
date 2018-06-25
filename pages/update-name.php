<?php
require_once('../inc/db.php');
require_once('../inc/func.php');
session_start();

$username = $_SESSION['username'];
$query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_array($query);
echo $row['name'];
?>