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
			if($_POST['act'] == "EDIT"){
				//echo 'edit';
				$id = $_POST['id'];
				
				$user = $_POST['username'];
				$pass = $_POST['password'];
				$name = $_POST['name'];
				$role = $_POST['role'];
				$balance = $_POST['balance'];
				$point = $_POST['point'];
				if ($user == "" || $pass == "" || $name == "" || $role == "" || $balance == "" || $point == ""){
					echo 'Error. Please fill all field!.';
				}else{
					mysqli_query($con, "UPDATE users SET username='$user', password='$pass', name='$name', role='$role', balance='$balance', point='$point' WHERE id='$id'");
					userlogs($username, "EDIT", "EDIT Username $user");
					echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
					echo 'Success!'.PHP_EOL;
					echo '====================='.PHP_EOL;
					echo 'Username '.$user.' edited.'.PHP_EOL;
					echo '====================='.PHP_EOL;
				}
			}elseif($_POST['act'] == "DELETE"){
				$user = $_POST['username'];
			
				$queryx = mysqli_query($con, "SELECT * FROM users WHERE username='$user'");
				$res = mysqli_num_rows($queryx);
				if ($res > 0){
					mysqli_query($con, "DELETE FROM users WHERE username='$user'");
					userlogs($username, "DELETE", "Delete Username $user");
					echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
					echo 'Success!'.PHP_EOL;
					echo '====================='.PHP_EOL;
					echo 'Username '.$user.' deleted.'.PHP_EOL;
					echo '====================='.PHP_EOL;
				}else{
					echo 'Account not found!';
				}
			}
		}else{
			?>
			<h2>Manage user</h2>
			<hr/>
			<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
				<thead>
					<tr>
						<th>ID</th>
                        <th>Username</th>
						<th>Password</th>
						<th>Name</th>
						<th>Role</th>
						<th>Balance</th>
						<th>Point</th>
						<th>Uplink</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$que = mysqli_query($con, "SELECT * FROM users ORDER BY id ASC");
					while($re = mysqli_fetch_array($que)){
						$role;
						if ($re['username'] == $username){
							$role = 'warning';
						}else{
							$role ='';
						}
						echo '<tr class="'.$role.'">';
						echo '<td>'.$re['id'].'</td>';
						echo '<td>'.$re['username'].'</td>';
						echo '<td>'.$re['password'].'</td>';
						echo '<td>'.$re['name'].'</td>';
						echo '<td>'.$re['role'].'</td>';
						echo '<td>'.$re['balance'].'</td>';
						echo '<td>'.$re['point'].'</td>';
						echo '<td>'.$re['uplink'].'</td>';
						/*if ($re['username'] !== $username){
							echo '<td><a href="#" data-toggle="modal" data-target="#'.$re['username'].'mdl" onclick="return false;">Delete</a> <a href="#" data-toggle="modal" data-target="#'.$re['username'].'mdle" onclick="return false;">Edit</a></td>';
						}else{
							echo '<td>-</td>';
						}*/
						echo '<td><a class="btn btn-default btn-xs" href="#" data-toggle="modal" data-target="#'.$re['username'].'mdl" onclick="return false;"><i class="fa fa-ban"></i></a> <a class="btn btn-default btn-xs" href="#" data-toggle="modal" data-target="#'.$re['username'].'mdle" onclick="return false;"><i class="fa fa-pencil"></i></a></td>';
						echo '</tr>';
						?>
						<!-- MODAL DELETE -->
						<div class="modal fade" id="<?php echo $re['username']; ?>mdl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title" id="myModalLabel">Information</h4>
									</div>
									<div class="modal-body">
										Are you sure want to delete '<?php echo $re['username']; ?>' ?
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-success" data-dismiss="modal">No</button>
										<button type="button" class="btn btn-danger" onclick="return false;" id="<?php echo $re['username']; ?>btn">Yes</button>
									</div>
								</div>
							</div>
						</div>
						<!-- END MODAL -->
						
						<!-- MODAL EDIT -->
						<div class="modal fade" id="<?php echo $re['username']; ?>mdle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title" id="myModalLabel">Edit '<?php echo $re['username'];?>'</h4>
									</div>
									<div class="modal-body">
										<form role="form" method="POST">
											<div class="form-group">
												<input class="form-control hidden" id="edit_id" type="text" disabled="" value="<?php echo $re['id'];?>">
												<div class="row">
													<div class="col-lg-6 col-md-6">
														<div class="form-group">
															<label>Username</label>
															<input class="form-control" id="edit_username<?php echo $re['id']; ?>" type="text" value="<?php echo $re['username'];?>">
														</div>
														<div class="form-group">
															<label>Password</label>
															<input class="form-control" id="edit_password<?php echo $re['id']; ?>" type="text" value="<?php echo $re['password'];?>">
														</div>
														<div class="form-group">
															<label>Name</label>
															<input class="form-control" id="edit_name<?php echo $re['id']; ?>" type="text" value="<?php echo $re['name'];?>">
														</div>
													</div>
													<div class="col-lg-6 col-md-6">
														<div class="form-group">
															<label>Role</label>
															<select class="form-control" id="edit_role<?php echo $re['id']; ?>">
																<?php
																$quex = mysqli_query($con, "SELECT * FROM roles");
																while($ro = mysqli_fetch_array($quex)){
																	$sel;
																	if ($ro['roles_code'] == $re['role']){
																		$sel = 'selected="selected"';
																	}else{
																		$sel = '';
																	}
																	echo '<option value="'.$ro['roles_code'].'" '.$sel.'>'.$ro['roles_name'].'</option>';
																}
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Balance</label>
															<input class="form-control" id="edit_balance<?php echo $re['id']; ?>" type="text" value="<?php echo $re['balance'];?>">
														</div>
														<div class="form-group">
															<label>Point</label>
															<input class="form-control" id="edit_point<?php echo $re['id']; ?>" type="text" value="<?php echo $re['point'];?>">
														</div>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="button" class="btn btn-primary" onclick="return false;" id="<?php echo $re['username']; ?>btne">Edit</button>
									</div>
								</div>
							</div>
						</div>
						<!-- END MODAL -->
						<script>
						document.getElementById("<?php echo $re['username']; ?>btn").onclick = function() {fnc<?php echo $re['username']; ?>(); updateInfo();};
						document.getElementById("<?php echo $re['username']; ?>btne").onclick = function() {fnc2<?php echo $re['username']; ?>(); updateInfo();};
						
						function fnc<?php echo $re['username']; ?>(){
							var user = '<?php echo $re['username']; ?>';
							$('#result').html('Proccessing...');
							$.ajax({
								data: 'act=DELETE' + '&username=' + user,
								url: './pages/admin-user_manage.php',
								method: 'POST', // or GET
								success: function(msg) {
									$('#result').html(msg)
								}
							});
							updateInfo();
							$('#<?php echo $re['username']; ?>mdl').modal('hide');
							setTimeout(function() {
								buka2('pages/admin-user_manage');
							}, 500);
						}
						
						function fnc2<?php echo $re['username']; ?>(){
							var id = '<?php echo $re['id']; ?>';
							var user = $('#edit_username<?php echo $re['id']; ?>').val();
							var pass = $('#edit_password<?php echo $re['id']; ?>').val();
							var name = $('#edit_name<?php echo $re['id']; ?>').val();
							var role = $('#edit_role<?php echo $re['id']; ?>').val();
							var balance = $('#edit_balance<?php echo $re['id']; ?>').val();
							var point = $('#edit_point<?php echo $re['id']; ?>').val();
							$('#result').html('Proccessing...');
							$.ajax({
								data: 'act=EDIT' + '&id=' + id + '&username=' + user + '&password=' + pass + '&name=' + name + '&role=' + role + '&balance=' + balance + '&point=' + point,
								url: './pages/admin-user_manage.php',
								method: 'POST', // or GET
								success: function(msg) {
									$('#result').html(msg)
								}
							});
							updateInfo();
							$('#<?php echo $re['username']; ?>mdle').modal('hide');
							setTimeout(function() {
								buka2('pages/admin-user_manage');
							}, 500);
						}
						
						$('.tooltipx').tooltip({
							selector: "[data-toggle=tooltip]",
							container: "body"
						})
						$("[data-toggle=popover]")
							.popover()
						</script>
						<?php
					}
					?>
				</tbody>
			</table>
			<script>
			$(document).ready(function() {
				$('#dataTables-example').DataTable({
					responsive: true,
					"order": [[ 0, "asc" ]]
				});
			});
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