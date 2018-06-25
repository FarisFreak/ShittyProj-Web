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
		$name = $_POST['name'];
		$pass = $_POST['pass'];
		if ($name == "" || $pass == ""){
			notice('danger', 'Error. Please fill all field.');
		}else{
			$query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
			$res = mysqli_num_rows($query);
			if ($res > 0){
				$res2 = mysqli_fetch_array($query);
				if ($pass == $res2['password']){
					if ($name == $res2['name']){
						notice('warning', 'Please make another new name!');
					}else{
						userlogs($username, 'SETTINGS', "Change name from (".$res2['name'].") to (".$name.")");
						mysqli_query($con, "UPDATE users SET name='$name' WHERE username='$username'");
						notice('success', 'Change name success!');
					}
				}else{
					notice('warning', 'Wrong password!');
				}
			}else{
				notice('danger', 'Account not found!');
			}
		}
	}else{
		?>
		<h2>Change Name</h2>
		<hr/>
		<form role="form" method="POST">
			<div class="form-group">
				<label>New Name</label>
				<input class="form-control" id="name" type="text">
			</div>
			<div class="form-group">
				<label>Password</label>
				<input class="form-control" id="pass" type="password">
			</div>
			<a id="change" class="btn btn-default pull-right" onclick="return false;">Change Name</a>
		</form>
		<script>
		document.getElementById("change").onclick = function() {change();};
		function change(){
			var pass = $('#pass').val();
			var name = $('#name').val();
			//$('#result').html('Proccessing...');
			$.ajax({
				data: 'pass=' + pass + '&name=' + name,
				url: './pages/setting-change_name.php',
				method: 'POST', // or GET
				success: function(msg) {
					$('#null').html(msg)
				}
			});
			updateInfo();
			buka2('pages/setting-change_name');
		}
		</script>
		<?php
	}
}
?>