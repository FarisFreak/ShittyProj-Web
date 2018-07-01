<?php
require_once('../inc/db.php');
require_once('../inc/func.php');
session_start();

if (!isset($_SESSION['username'])){
	echo '<h1>Login First!</h1>';
	//header('location: ./index.php');
}else{
	$username = $_SESSION['username'];
	if (getrole() == 'RESELLER'){
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			$user = $_POST['username'];
			$pass = $_POST['password'];
			$name = $_POST['name'];
			if ($user == '' || $pass == '' || $name == ''){
				echo 'Error. Please fill all field!';
			}else{
				$q = mysqli_query($con, "SELECT * FROM users WHERE username = '$user'");
				$res = mysqli_num_rows($q);
				if ($res > 0){
					echo 'Username already exist!';
				}else{
					$price = getPrice();
					$curx = currentBalance($username) - $price;
					if ($curx < 0){
						echo 'INSUFFICIENT BALANCE.';
					}else{
						mysqli_query($con, "INSERT INTO users (id, username, password, role, name, uplink, balance, point) VALUES ('', '$user', '$pass', 'MEMBER', '$name', '$username', 0, 0)");
						creditBalance($username, $price, 0);
						userlogs($username, 'REGISTER', "(RESELLER) Register user ($user, $pass, $name, MEMBER) from ($username). Current balance : ".currentBalance($username)."");
						echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
						echo 'Success!'.PHP_EOL;
						echo '====================='.PHP_EOL;
						echo 'Username : '.$user.PHP_EOL;
						echo 'Password : '.$pass.PHP_EOL;
						echo 'Name : '.$name.PHP_EOL;
						echo 'Uplink : '.$username.PHP_EOL;
						echo '====================='.PHP_EOL.'-';
						echo getPrice().' Credits for add new user'.PHP_EOL;
						echo 'Your current balance : '.currentBalance($username);
						
					}
				}
			}
		}else{
			?>
			<h2>Register New User</h2>
			<hr/>
			<form role="form" method="POST">
				<div class="form-group">
					<label>Username</label>
					<input class="form-control" id="new_username" type="text">
				</div>
				<div class="form-group">
					<label>Password</label>
					<input class="form-control" id="new_password" type="password">
				</div>
				<div class="form-group">
					<label>Name</label>
					<input class="form-control" id="new_name">
				</div>
			<a id="registerx" class="btn btn-default pull-right" onclick="return false;">Add User</a>
			</form>
			<script>
			document.getElementById("registerx").onclick = function() {register(); updateInfo();};
			function register(){
				var user = $('#new_username').val();
				var pass = $('#new_password').val();
				var name = $('#new_name').val();
				$('#result').html('Proccessing...');
				$.ajax({
					data: 'username=' + user + '&password=' + pass + '&name=' + name,
					url: './pages/user-add.php',
					method: 'POST', // or GET
					success: function(msg) {
						$('#result').html(msg)
					}
				});
				updateInfo();
				buka2('pages/user-add');
			}
			</script>
			<?php
		}
	}
}
?>