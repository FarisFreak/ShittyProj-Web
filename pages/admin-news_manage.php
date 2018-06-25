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
			if($_POST['act'] == "DELETE"){
				$id = $_POST['id'];
				$query = mysqli_query($con, "SELECT * FROM news WHERE id='$id'");
				$res = mysqli_num_rows($query);
				$re = mysqli_fetch_array($query);
				if ($res > 0){
					userlogs($username, 'NEWS', $username.' deleted an news (Title : '.$re['title'].').');
					notice('success', 'Message (title : '.$re['title'].') successfully deleted.');
					mysqli_query($con, "DELETE FROM news WHERE id='$id'");
				}else{
					notice('danger', 'FATAL ERROR. Message not found!');
				}
			}elseif($_POST['act'] == "EDIT"){
				$id= $_POST['id'];
				$title = $_POST['title'];
				$type = $_POST['type'];
				$message = $_POST['message'];
				
				if ($title == "" || $type == "" || $message == ""){
					notice('danger', 'Please fill all field!');
				}else{
					mysqli_query($con,"UPDATE news SET title='$title', type='$type', info='$message' WHERE id='$id'");
					userlogs($username, 'NEWS', $username.' edit an news (Title : '.$title.')');
					notice('success', 'An news has been edited (Title : '.$title.')');
				}
			}
		}else{
			?>
			<h2>Manage News</h2>
			<hr/>
			<table width="100%" class="table table-striped table-bordered table-hover" id="news-manage">
				<thead>
					<tr>
						<th>ID</th>
                        <th>Date</th>
						<th>Type</th>
						<th>Title</th>
						<th>Message</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$que = mysqli_query($con, "SELECT * FROM news");
					while($re = mysqli_fetch_array($que)){
						echo '<tr>';
						echo '<td>'.$re['id'].'</td>';
						echo '<td>['.$re['date'].']</td>';
						echo '<td>'.$re['type'].'</td>';
						echo '<td>'.$re['title'].'</td>';
						echo '<td>'.$re['info'].'</td>';
						echo '<td><a class="btn btn-default btn-xs" data-toggle="modal" data-target="#del'.$re['id'].'mdl" onclick="return false;"><i class="fa fa-ban"></i></a> <a class="btn btn-default btn-xs" data-toggle="modal" data-target="#edit'.$re['id'].'mdl" onclick="return false;"><i class="fa fa-pencil"></i></a></td>';
						echo '</tr>';
						?>
						<!-- MODAL DELETE -->
						<div class="modal fade" id="del<?php echo $re['id']; ?>mdl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title" id="myModalLabel">Information</h4>
									</div>
									<div class="modal-body">
										Are you sure want to delete '<?php echo $re['title']; ?>' ?
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-success" data-dismiss="modal">No</button>
										<button type="button" class="btn btn-danger" onclick="return false;" id="del<?php echo $re['id']; ?>btn">Yes</button>
									</div>
								</div>
							</div>
						</div>
						<!-- END MODAL -->
						
						<!-- MODAL EDIT -->
						<div class="modal fade" id="edit<?php echo $re['id']; ?>mdl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title" id="myModalLabel">Information</h4>
									</div>
									<div class="modal-body">
										<form role="form" method="POST">
											<input class="form-control hidden" id="id<?php $re['id'];?>" type="text" value="<?php $re['id'];?>">
											<div class="form-group">
												<label>Title</label>
												<input class="form-control" id="title<?php echo $re['id']; ?>" type="text" value="<?php echo $re['title'];?>">
											</div>
											<div class="form-group">
												<label>Type</label>
												<select class="form-control" id="type<?php echo $re['id'];?>">
													<?php
													if ($re['type'] == "INFO"){
														echo '<option value="INFO" selected="selected">INFO</option>';
														echo '<option value="PROMO">PROMO</option>';
													}elseif($re['type'] == "PROMO"){
														echo '<option value="INFO">INFO</option>';
														echo '<option value="PROMO" selected="selected">PROMO</option>';
													}
													?>
												</select>
											</div>
											<div class="form-group">
												<label>Message</label>
												<input class="form-control" id="message<?php echo $re['id']; ?>" type="text" value="<?php echo $re['info'];?>">
											</div>
										</form>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="button" class="btn btn-primary" onclick="return false;" id="edit<?php echo $re['id']; ?>btn">Edit</button>
									</div>
								</div>
							</div>
						</div>
						<script>
						document.getElementById("del<?php echo $re['id']; ?>btn").onclick = function() {fnc<?php echo $re['id']; ?>del(); updateInfo();};
						document.getElementById("edit<?php echo $re['id']; ?>btn").onclick = function() {fnc<?php echo $re['id']; ?>edit(); updateInfo();};
						
						function fnc<?php echo $re['id']; ?>del(){
							var id = '<?php echo $re['id']; ?>';
							//$('#result').html('Proccessing...');
							$.ajax({
								data: 'act=DELETE' + '&id=' + id,
								url: './pages/admin-news_manage.php',
								method: 'POST', // or GET
								success: function(msg) {
									$('#null').html(msg)
								}
							});
							updateInfo();
							$('#del<?php echo $re['id']; ?>mdl').modal('hide');
							setTimeout(function() {
								buka2('pages/admin-news_manage');
							}, 500);
						}
						
						function fnc<?php echo $re['id']; ?>edit(){
							var id = '<?php echo $re['id']; ?>';
							var title = $('#title<?php echo $re['id']; ?>').val();
							var type = $('#type<?php echo $re['id']; ?>').val();
							var message = $('#message<?php echo $re['id']; ?>').val();
							//$('#result').html('Proccessing...');
							$.ajax({
								data: 'act=EDIT' + '&id=' + id + '&title=' + title + '&type=' + type + '&message=' + message,
								url: './pages/admin-news_manage.php',
								method: 'POST', // or GET
								success: function(msg) {
									$('#null').html(msg)
								}
							});
							updateInfo();
							$('#edit<?php echo $re['id']; ?>mdl').modal('hide');
							setTimeout(function() {
								buka2('pages/admin-news_manage');
							}, 500);
						}
						</script>
						<!-- END MODAL -->
						<?php
					}
					?>
				</tbody>
			</table>
			<script>
			$(document).ready(function() {
				$('#news-manage').DataTable({
					responsive: true,
					"order": [[ 0, "DESC" ]]
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