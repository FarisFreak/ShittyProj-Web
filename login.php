<!DOCTYPE html>
<html lang="en">
<?php
require_once('./inc/db.php');
require_once('./inc/func.php');
headpage('Login');
?>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
               <div class="login-panel panel panel-default">
					<div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="POST">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!--<div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>-->
                                <!-- Change this to a button or input when using this as a form -->
								<input type="submit" value="Login" class="btn btn-lg btn-success btn-block">
                                <!--<a href="" class="btn btn-lg btn-success btn-block">Login</a>-->
                            </fieldset>
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
	$username = mysqli_real_escape_string($con, $_POST['username']);
	$password = mysqli_real_escape_string($con, $_POST['password']);
	
	$query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");
	$res = mysqli_num_rows($query);
	if ($res == 1){
		session_start();
		$_SESSION['username'] = $username;
		userlogs($username, "LOGIN", "$username successfully login from IP : ".getIP());
		header('location: ./index.php');
	}else{
		//echo 'SALAH';
		?>
		<div class="modal fade" id="overlay">
		  <div class="modal-dialog">
			<div style="padding-top: 5px;">
				<div class="alert alert-danger" style="margin: 0 auto;">
					Wrong username / password
				</div>
			</div>
		  </div>
		</div>
		<script>
			$('#overlay').modal('show');
			
			setTimeout(function() {
				$('#overlay').modal('hide');
			}, 2000);
		</script>
		<?php
	}
} elseif (!isset($_SESSION['username'])) {
	
}
?>
                        </form>
                    </div>
				</div>
            </div>
        </div>
    </div>
</body>
</html>