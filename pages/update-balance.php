<?php
require_once('../inc/db.php');
require_once('../inc/func.php');
session_start();

$username = $_SESSION['username'];
echo balance(currentBalance($username));
?>