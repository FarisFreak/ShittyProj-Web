<?php
require_once('../inc/db.php');
require_once('../inc/func.php');
session_start();

if (!isset($_SESSION['username'])){
	echo '<h1>Login First!</h1>';
	//header('location: ./index.php');
}else{
	$username = $_SESSION['username'];
	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		
	}else{
		?>
		<h2>Register New User</h2>
		<hr/>
		<div class="form-group">
            <label>HDSN</label>
            <input class="form-control" id="hdsn">
        </div>
		<?php
	}
}
?>