<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'dbproj';
global $con;
$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
?>