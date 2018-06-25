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
			$title = $_POST['title'];
			$userprice = $_POST['userprice'];
			mysqli_query($con, "UPDATE config SET title='$title', pricereg='$userprice'");
			userlogs($username, "CONFIG", "Config updated. Title = $title, User Register Price = $userprice");
			notice('success', 'Config has been saved');
		}else{
			$query = mysqli_query($con, "SELECT * FROM config");
			$row = mysqli_fetch_array($query);
			?>
			<h2>Admin Config</h2>
			<hr/>
			<form role="form" method="POST">
				<div class="row">
					<div class="col-lg-6"><!-- Web Config-->
						<div class="form-group">
							<label>Title</label>
							<input class="form-control" id="edit_title" value="<?php echo $row['title']; ?>">
						</div>
					</div>
					<div class="col-lg-6"><!-- Panel Config-->
						<div class="form-group">
							<label>'Add User' Price</label>
							<input class="form-control" id="edit_user-price" value="<?php echo $row['pricereg']; ?>">
						</div>
					</div>
				</div>
				<a id="config" class="btn btn-default pull-right" onclick="return false;">Save</a>
			</form>
			<script>
			document.getElementById("config").onclick = function() {config(); updateInfo();};
			function config(){
				var title = $('#edit_title').val();
				var price = $('#edit_user-price').val();
				//$('#result').html('Proccessing...');
				$.ajax({
					data: 'title=' + title + '&userprice=' + price,
					url: './pages/admin-config.php',
					method: 'POST', // or GET
					success: function(msg) {
						$('#null').html(msg)
					}
				});
				updateInfo();
				setTimeout(function() {
					location.reload();
				}, 1000);
				//buka2('pages/admin-config');
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