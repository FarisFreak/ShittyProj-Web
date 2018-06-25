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
		
		$queryx = mysqli_query($con, "SELECT * FROM hdsn WHERE hdsn = '$hdsn'");
		$res = mysqli_num_rows($queryx);
		if ($res > 0){
			$_nuldate = mysqli_fetch_array($queryx);
			$dateHDSN = $_nuldate['expired'];
			
			$remain = dayCount(dateNow(), $dateHDSN);
			
			$queryPBTable = mysqli_query($con, "SELECT * FROM pb_price WHERE id = '$dur'");
			$_PBTable = mysqli_fetch_array($queryPBTable);
			$name = $_PBTable['name'];
			$price = $_PBTable['price'];
			$duration = $_PBTable['duration'];
			$points = $_PBTable['points'];
			
			$curx = currentBalance($username) - $price;
			if ($curx < 0){
				echo 'INSUFFICIENT BALANCE.';
			}else{
				if ($remain > 0){
					$count = dayAdd($dateHDSN, $duration);
					mysqli_query($con, "UPDATE hdsn SET expired='$count' WHERE hdsn='$hdsn'");
					creditBalance($username, $price, $points);
					echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
					echo 'Success!'.PHP_EOL;
					echo '====================='.PHP_EOL;
					echo 'HDSN : '.$hdsn.PHP_EOL;
					echo 'Expiry : '.$count.' (+'.$duration.' days)'.PHP_EOL;
					echo 'Price : '.$price.PHP_EOL;
					echo 'Current Balance : '.currentBalance($username).PHP_EOL;
					echo '====================='.PHP_EOL;
					echo "You've got ".$points.' points!'.PHP_EOL;
					userlogs($username, "EXTEND", "Extend HDSN (".$hdsn.") Menu Details (".$name.", ".$price.") Current Balance (".currentBalance($username).")");
				}else{
					$count = dayAdd(dateNow(), $duration);
					mysqli_query($con, "UPDATE hdsn SET expired='$count' WHERE hdsn='$hdsn'");
					creditBalance($username, $price, $points);
					echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
					echo 'Success!'.PHP_EOL;
					echo '====================='.PHP_EOL;
					echo 'HDSN : '.$hdsn.PHP_EOL;
					echo 'Expiry : '.$count.' (+'.$duration.' days)'.PHP_EOL;
					echo 'Price : '.$price.PHP_EOL;
					echo 'Current Balance : '.balance(currentBalance($username)).PHP_EOL;
					echo '====================='.PHP_EOL;
					echo "You've got ".$points.' points!'.PHP_EOL;
					userlogs($username, "EXTEND", "Extend HDSN (".$hdsn.") Menu Details (".$name.", ".$price.") Current Balance (".currentBalance($username).")");
				}
			}
		}else{
			echo 'HDSN Invalid';
		}
	}
	else{
	?>
	<h2>Extend HDSN - Point Blank</h2>
	<hr/>
	<form role="form" method="POST">
		<div class="form-group">
            <label>HDSN</label>
			<select class="form-control" id="hdsn">
				<?php
					$query = mysqli_query($con, "SELECT * FROM hdsn WHERE owner_id = '$id'");
					while($row = mysqli_fetch_array($query)){
						$_hdsn = $row['hdsn'];
						echo '<option value = "'.$_hdsn.'">'.$_hdsn.'</option>';
					}
					$res = mysqli_num_rows($query);
					
				?>
			</select>
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
		<a id="extend" class="btn btn-default pull-right <?php if($res<1)echo'disabled';?>" onclick="return false;">Extend</a>
	</form>
	<script>
	document.getElementById("extend").onclick = function() {extend();};
	function extend(){
		var hdsn = $('#hdsn').val();
		var duration = $('#duration').val();
		$('#result').html('Proccessing...');
		$.ajax({
			data: 'hdsn=' + hdsn + '&duration=' + duration,
			url: './pages/pb-extend.php',
			method: 'POST', // or GET
			success: function(msg) {
				$('#result').html(msg)
			}
		});
		updateInfo();
		buka2('pages/pb-extend');
	}
	</script>
	<?php
	}
}
?>