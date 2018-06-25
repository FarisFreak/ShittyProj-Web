<?php
require_once('../inc/db.php');
require_once('../inc/func.php');
session_start();

if (!isset($_SESSION['username'])){
	echo '<h1>Login First!</h1>';
	//header('location: ./index.php');
}else{
	$username = $_SESSION['username'];
	if (getrole() == 'ADMIN'){
		if ($_SERVER['REQUEST_METHOD'] === 'POST'){
			$user = $_POST['username'];
			$pass = $_POST['password'];
			$name = $_POST['name'];
			$balance = $_POST['balance'];
			$point = $_POST['point'];
			$level = $_POST['level'];
			if ($user == "" || $pass == "" || $balance == "" || $point == "" || $level == ""){
				echo 'Error. Please fill all field!';
			}else{
				$query = mysqli_query($con, "SELECT * FROM users WHERE username='$user'");
				$res = mysqli_num_rows($query);
				if ($res == 0){
					mysqli_query($con, "INSERT INTO users (id, username, password, name, role, balance, point, uplink) VALUES ('', '$user', '$pass', '$name', '$level', '$balance', '$point', '$username')");
					userlogs($username, 'REGISTER', "Register user ($user, $pass, $name, $level, $balance, $point) from ($username)");
					echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
					echo 'Success!'.PHP_EOL;
					echo '====================='.PHP_EOL;
					echo 'Username : '.$user.PHP_EOL;
					echo 'Password : '.$pass.PHP_EOL;
					echo 'Name : '.$name.PHP_EOL;
					echo 'Level : '.$level.PHP_EOL;
					echo 'Balance : '.$balance.PHP_EOL;
					echo 'Point : '.$point.PHP_EOL;
					echo 'Uplink : '.$username.PHP_EOL;
					echo '====================='.PHP_EOL;
				}else{
					echo 'Username already exist!';
				}
			}
		}else{
			?>
			<h2>Register New User</h2>
			<hr/>
			<form role="form" method="POST">
				<div class="row">
					<div class="col-lg-6 col-md-6">
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
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="form-group">
							<label>Balance</label>
							<input class="form-control" id="new_balance">
						</div>
						<div class="form-group">
							<label>Point</label>
							<input class="form-control" id="new_point">
						</div>
						<div class="form-group">
							<label>Level</label>
							<select class="form-control" id="new_level">
								<?php
								$que = mysqli_query($con, "SELECT * FROM roles");
								while($ro = mysqli_fetch_array($que)){
									echo '<option value="'.$ro['roles_code'].'">'.$ro['roles_name'].'</option>';
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<a id="register" class="btn btn-default pull-right" onclick="return false;">Add User</a>
			</form>
			<script>
			document.getElementById("register").onclick = function() {register(); updateInfo();};
			function register(){
				var user = $('#new_username').val();
				var pass = $('#new_password').val();
				var name = $('#new_name').val();
				var balance = $('#new_balance').val();
				var point = $('#new_point').val();
				var level = $('#new_level').val();
				$('#result').html('Proccessing...');
				$.ajax({
					data: 'username=' + user + '&password=' + pass + '&name=' + name + '&balance=' + balance + '&point=' + point + '&level=' + level,
					url: './pages/admin-user_add.php',
					method: 'POST', // or GET
					success: function(msg) {
						$('#result').html(msg)
					}
				});
				updateInfo();
				buka2('pages/admin-user_add');
			}
			</script>
			<?php
		}
	}else{
		?>
		<script>
		pagenull();
		</script>
		<?php
	}
}
?>