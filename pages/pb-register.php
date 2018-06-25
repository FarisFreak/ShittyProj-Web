<?php
require_once('../inc/db.php');
require_once('../inc/func.php');
session_start();

if (!isset($_SESSION['username'])){
	echo '<h1>Login First!</h1>';
	//header('location: ./index.php');
}else{
	$username = $_SESSION['username'];
	$query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
	$row = mysqli_fetch_array($query);
	$id = $row['id'];
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST'){
		$hdsn = $_POST['hdsn'];
		$dur = $_POST['duration'];
		if ($hdsn == ""){
			echo 'Invalid HDSN.';
		}else{
			$queryx = mysqli_query($con, "SELECT * FROM hdsn WHERE hdsn = '$hdsn'");
			$row = mysqli_num_rows($queryx);
			if ($row > 0){
				echo 'HDSN already registered.';
			}else{
				$queryPBTable = mysqli_query($con, "SELECT * FROM pb_price WHERE id = '$dur'");
				$_PBTable = mysqli_fetch_array($queryPBTable);
				$name = $_PBTable['name'];
				$price = $_PBTable['price'];
				$duration = $_PBTable['duration'];
				$points = $_PBTable['points'];
				
				$totalDuration = dayAdd(dateNow(), $duration);
				$curx = currentBalance($username) - $price;
				if ($curx < 0){
					echo 'INSUFFICIENT BALANCE.';
				}else{
					mysqli_query($con, "INSERT INTO hdsn (id, owner_id, hdsn, expired) VALUES ('', '$id', '$hdsn', '$totalDuration')");
					creditBalance($username, $price, $points);
					echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
					echo 'Success!'.PHP_EOL;
					echo '====================='.PHP_EOL;
					echo 'HDSN : '.$hdsn.PHP_EOL;
					echo 'Expiry : '.$totalDuration.' ('.$duration.' days)'.PHP_EOL;
					echo 'Price : '.$price.PHP_EOL;
					echo 'Current Balance : '.currentBalance($username).PHP_EOL;
					echo '====================='.PHP_EOL;
					echo "You've got ".$points.' points!'.PHP_EOL;
					userlogs($username, "REGISTER", "Register HDSN (".$hdsn.") Menu Details (".$name.", ".$price.") Current Balance (".currentBalance($username).")");
				}
				
			}
		}
		
	}
	else{
	?>
	<h2>Register HDSN - Point Blank</h2>
	<hr/>
	<form role="form" method="POST">
		<div class="form-group">
            <label>HDSN</label>
            <input class="form-control" id="hdsn">
        </div>
		<div class="form-group">
            <label>Duration</label>
            <select class="form-control" id="duration">
				<?php
				$query = mysqli_query($con, "SELECT * FROM pb_price");
				while($row = mysqli_fetch_array($query)){
					echo '<option value="'.$row['id'].'">'.$row['name'].' ('.number_format($row['price']).')</option>';
				}
				?>
            </select>
        </div>
		<a id="register" class="btn btn-default pull-right" onclick="return false;">Register</a>
	</form>
	<script>
	document.getElementById("register").onclick = function() {register(); updateInfo();};
	function register(){
		var hdsn = $('#hdsn').val();
		var duration = $('#duration').val();
		$('#result').html('Proccessing...');
		$.ajax({
			data: 'hdsn=' + hdsn + '&duration=' + duration,
			url: './pages/pb-register.php',
			method: 'POST', // or GET
			success: function(msg) {
				$('#result').html(msg)
			}
		});
		updateInfo();
		buka2('pages/pb-register');
	}
	</script>
	<?php
	}
}
?>