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
		$oldpass = $_POST['oldpass'];
		$newpass = $_POST['newpass'];
		if ($oldpass == "" || $newpass == ""){
			notice('danger', 'Error. Please fill all field.');
		}else{
			$query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
			$res = mysqli_num_rows($query);
			if ($res > 0){
				$res2 = mysqli_fetch_array($query);
				if ($oldpass == $res2['password']){
					if ($newpass == $oldpass){
						notice('warning', 'Please make another new password!');
					}else{
						userlogs($username, 'SETTINGS', "Change password from (".$res2['password'].") to (".$newpass.")");
						mysqli_query($con, "UPDATE users SET password='$newpass' WHERE username='$username'");
						notice('success','Change password success!');
					}
				}else{
					notice('warning',"Old password doesn't match with the current password.");
				}
			}else{
				notice('danger','Account not found!');
			}
		}
	}else{
		?>
		<h2>Change Password</h2>
		<hr/>
		<form role="form" method="POST">
			<div class="form-group">
				<label>Old Password</label>
				<input class="form-control" id="oldPass" type="password">
			</div>
			<div class="form-group">
				<label>New Password</label>
				<input class="form-control" id="newPass" type="password">
			</div>
			<a id="change" class="btn btn-default pull-right" onclick="return false;">Change Password</a>
		</form>
		<script>
		document.getElementById("change").onclick = function() {change();};
		function change(){
			var oldpass = $('#oldPass').val();
			var newpass = $('#newPass').val();
			//$('#result').html('Proccessing...');
			$.ajax({
				data: 'oldpass=' + oldpass + '&newpass=' + newpass,
				url: './pages/setting-change_password.php',
				method: 'POST', // or GET
				success: function(msg) {
					$('#null').html(msg)
				}
			});
			updateInfo();
			buka2('pages/setting-change_password');
		}
		</script>
		<?php
	}
}
?>