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
			$type = $_POST['type'];
			$message = $_POST['message'];
			
			if ($title == "" || $type == "" || $message == ""){
				echo "Error. Please fill all field!";
			}else{
				mysqli_query($con, "INSERT INTO news (id, type, date, title, info) VALUES ('', '$type', '".dateNow()."', '$title', '$message')");
				userlogs($username, 'NEWS', "$username add news (".dateNow().", $title, $message, $type)");
				echo '['.dateNow().'] '.Date('H:i:s').''.PHP_EOL;
				echo 'Success!'.PHP_EOL;
				echo '====================='.PHP_EOL;
				echo "Add news (".dateNow().", $title, $message, $type)".PHP_EOL;
				echo '====================='.PHP_EOL;
			}
		}else{
			?>
			<h2>Add News</h2>
			<hr/>
			<form role="form" method="POST">
				<div class="form-group">
					<label>Title</label>
					<input class="form-control" id="news_title" type="text">
					<label>Type</label>
					<select class="form-control" id="news_type">
						<option value="INFO">INFO</option>
						<option value="PROMO">PROMO</option>
					</select>
				</div>
				<div class="form-group">
					<label>Message</label>
					<textarea class="form-control" rows="3" id="news_message"></textarea>
				<div>
				<div class="form-group">
					<a id="add-news" class="btn btn-default pull-right" onclick="return false;">Add User</a>
				</div>
			</form>
			<script>
			document.getElementById("add-news").onclick = function() {addnews(); updateInfo();};
			function addnews(){
				var title = $('#news_title').val();
				var type = $('#news_type').val();
				var message = $('#news_message').val();
				$('#result').html('Proccessing...');
				$.ajax({
					data: 'title=' + title + '&type=' + type + '&message=' + message,
					url: './pages/admin-news_add.php',
					method: 'POST', // or GET
					success: function(msg) {
						$('#result').html(msg)
					}
				});
				updateInfo();
				buka2('pages/admin-news_add');
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