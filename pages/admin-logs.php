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
		?>
		<div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            User Logs
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Info</th>
                                    </tr>
                                </thead>
                                <tbody>
								<?php
								$query2 = mysqli_query($con, "SELECT * FROM logs ORDER BY date DESC");
								while($res = mysqli_fetch_array($query2)){
									echo '<tr>';
									echo '<td>'.$res['username'].'</td>';
									echo '<td>'.$res['date'].'</td>';
									echo '<td>'.$res['action'].'</td>';
									echo '<td>'.$res['info'].'</td>';
									echo '</tr>';
								}
								?>
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
	<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
			"order": [[ 1, "desc" ]]
        });
    });
    </script>
		<?php
	}else{
		?>
		<script>
		pagenull();
		</script>
		<?php
	}
}
?>