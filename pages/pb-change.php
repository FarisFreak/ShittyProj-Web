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
		$newhdsn = $_POST['new-hdsn'];
		
		if ($newhdsn == ""){
			echo 'Invalid HDSN';
		}else{
			$query2 = mysqli_query($con, "SELECT * FROM hdsn WHERE hdsn = '$hdsn'");
			$res = mysqli_num_rows($query2);
			
			$query2x = mysqli_query($con, "SELECT * FROM hdsn WHERE hdsn = '$newhdsn'");
			$resx = mysqli_num_rows($query2x);
			if($resx > 0){
				echo 'New HDSN already registered.';
			}else{
				if($res > 0){
					mysqli_query($con, "UPDATE hdsn SET hdsn='$newhdsn' WHERE hdsn='$hdsn'");
					echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
					echo 'Success!'.PHP_EOL;
					echo '====================='.PHP_EOL;
					echo 'Old HDSN : '.$hdsn.PHP_EOL;
					echo 'New HDSN : '.$newhdsn.PHP_EOL;
					echo '====================='.PHP_EOL;
					userlogs($username, "CHANGE", "Change HDSN $hdsn To $newhdsn");
				}else{
					echo 'Current HDSN not registered';
				}
			}
		}
	}
	else{
	?>
	<h2>Change HDSN - Point Blank</h2>
	<hr/>
	<form role="form" method="POST">
		<div class="form-group">
            <label>Old HDSN</label>
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
            <label>New HDSN</label>
            <input class="form-control" id="new-hdsn">
        </div>
		<a id="change" class="btn btn-default pull-right <?php if($res<1)echo'disabled';?>" onclick="return false;">Change</a>
	</form>
	<script>
	document.getElementById("change").onclick = function() {change(); updateInfo();};
	function change(){
		var hdsn = $('#hdsn').val();
		var changehdsn = $('#new-hdsn').val();
		$('#result').html('Proccessing...');
		$.ajax({
			data: 'hdsn=' + hdsn + '&new-hdsn=' + changehdsn,
			url: './pages/pb-change.php',
			method: 'POST', // or GET
			success: function(msg) {
				$('#result').html(msg)
			}
		});
		updateInfo();
		buka2('pages/pb-change');
	}
	</script>
	<?php
	}
}
?>