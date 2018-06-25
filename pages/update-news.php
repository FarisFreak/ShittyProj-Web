<?php
require_once('../inc/db.php');
require_once('../inc/func.php');
session_start();

$quernews = mysqli_query($con, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
while($row = mysqli_fetch_array($quernews)){
	echo '<tr>';
	echo '<td style="width: 0px;"><code>['.$row['date'].']</code></td>';
	echo '<td style="width: 0px;"><kbd>'.$row['type'].'</kbd></td>';
	echo '<td><a href="#" onclick="return false;" data-toggle="modal" data-target="#f'.$row['id'].'mdl">'.$row['title'].'</a></td>';
	echo '</tr>';
	?>
	<div class="modal fade" id="f<?php echo $row['id']; ?>mdl" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title"><?php echo $row['title']; ?></h4>
				</div>
				<div class="modal-body">
					<?php echo $row['info']; ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
				</div>
			</div>
		</div>
	</div>
<?php
}
?>