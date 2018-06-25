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
		$hdsn = $_POST['hdsn'];
		mysqli_query($con, "DELETE FROM hdsn WHERE hdsn='$hdsn'");
		echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
		echo 'Success!'.PHP_EOL;
		echo '====================='.PHP_EOL;
		echo 'HDSN '.$hdsn.' deleted.'.PHP_EOL;
		echo '====================='.PHP_EOL;
		userlogs($username, "DELETE", "Delete HDSN $hdsn");
	}else{
		$query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
		$row = mysqli_fetch_array($query);
		$id = $row['id'];
		?>
		<h2>Check HDSN - Point Blank</h2>
		<hr/>
		<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>No.</th>
						<th>HDSN</th>
						<th>Expiry Date</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php
				$query = mysqli_query($con, "SELECT * FROM hdsn WHERE owner_id = '$id'");
				$res = mysqli_num_rows($query);
				if ($res < 1){
					echo '<tr class="info">';
					echo '<td></td>';
					echo '<td></td>';
					echo '<td>NO DATA</td>';
					echo '<td></td>';
					echo '<td></td>';
					echo '</tr>';
				}else{
					$num = 1;
					while($row = mysqli_fetch_array($query)){
						$cur = dayCount(dateNow(), $row['expired']); if ($cur >= 0){ $cur = 'success'; }else{$cur = 'danger';}
						echo '<tr class="'.$cur.'">';
						echo '<td>'.$num.'</td>';
						echo '<td>'.$row['hdsn'].'</td>';
						if ($cur == 'success') { echo '<td>'.$row['expired'].' ('.dayCount(dateNow(), $row['expired']).' days)</td>'; }else{ echo '<td>'.$row['expired'].'</td>'; } 
						if($cur == 'success'){ $cur = 'Active'; }else{ $cur = 'Expired'; }
						echo '<td>'.$cur.'</td>';
						echo '<td class="tooltipx"><a href="#" onclick="return false;" data-toggle="modal" data-target="#'.$row['hdsn'].'mdl"><i class="fa fa-ban" data-toggle="tooltip" data-placement="top" title="Delete"></i></a></td>';
						echo '</tr>';
						?>
						<!-- MODAL -->
						<div class="modal fade" id="<?php echo $row['hdsn']; ?>mdl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
												<h4 class="modal-title" id="myModalLabel">Information</h4>
											</div>
											<div class="modal-body">
												Are you sure want to delete <?php echo $row['hdsn']; ?> ?
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-success" data-dismiss="modal">No</button>
												<button type="button" class="btn btn-danger" onclick="return false;" id="<?php echo $row['hdsn']; ?>btn">Yes</button>
											</div>
										</div>
										<!-- /.modal-content -->
									</div>
									<!-- /.modal-dialog -->
								</div>
						<!-- END MODAL -->
						<script>
						document.getElementById("<?php echo $row['hdsn']; ?>btn").onclick = function() {fnc<?php echo $row['hdsn']; ?>(); updateInfo();};
						function fnc<?php echo $row['hdsn']; ?>(){
							var hdsn = '<?php echo $row['hdsn']; ?>';
							$('#result').html('Proccessing...');
							$.ajax({
								data: 'hdsn=' + hdsn,
								url: './pages/pb-check.php',
								method: 'POST', // or GET
								success: function(msg) {
									$('#result').html(msg)
								}
							});
							updateInfo();
							$('#<?php echo $row['hdsn']; ?>mdl').modal('hide');
							setTimeout(function() {
								buka2('pages/pb-check');
							}, 500);
							//buka2('pages/pb-check');
						}
						$('.tooltipx').tooltip({
							selector: "[data-toggle=tooltip]",
							container: "body"
						})
						$("[data-toggle=popover]")
							.popover()
						</script>
						<?php
						$num++;
					}
				}
				?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
?>