<!DOCTYPE html>
<html lang="en">

<?php
session_start();
require_once('./inc/db.php');
require_once('./inc/func.php');

if (!isset($_SESSION['username'])) header('location: ./login.php');
$username = $_SESSION['username'];

$query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
$row = mysqli_fetch_array($query);
$id = $row['id'];

$query2 = mysqli_query($con, "SELECT * FROM hdsn WHERE owner_id = '$id'");
$count = mysqli_num_rows($query2);

$query3 = mysqli_query($con, "SELECT * FROM roles WHERE roles_code = '".getrole()."'");
$_role = mysqli_fetch_assoc($query3);
$role = $_role['roles_name'];

headpage('Main');
?>


<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="./"><?php echo gettitle();?></a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i><span id="usernamex"><?php echo $row['name']; ?></span> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <!--<li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>-->
                        <li><a href="#" onclick="return false;" id="logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse" id="menunav">
                    <ul class="nav" id="side-menu">
					
                        <!--<li>
                            <a href="index.html"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>-->
						<li>
							<a href="#"><i class="fa fa-gamepad fa-fw"></i> Games</a>
							<ul id="nul-games" class="nav nav-second-level">
                                <li>
                                    <a href="#">Point Blank</a>
									<ul id="nul-pb" class="nav nav-third-level">
										<li>
											<a href="#" id="pb-reg" onclick="return false;">Register HDSN</a>
										</li>
										<li>
											<a href="#" id="pb-change" onclick="return false;">Change HDSN</a>
										</li>
										<li>
											<a href="#" id="pb-extend" onclick="return false;">Extend HDSN</a>
										</li>
										<li>
											<a href="#" id="pb-check" onclick="return false;">Check HDSN</a>
										</li>
									</ul>
                                </li>
                                <!--<li>
                                    <a href="#" onclick="pages('ros.html');">Rules Of Survival</a>
                                </li>-->
                            </ul>
						</li>
						<?php
						if (getrole() == 'RESELLER'){
							?>
							<li>
								<a href="#"><i class="fa fa-users fa-fw"></i> User</a>
								<ul class="nav nav-second-level">
									<li>
										<a href="#" id="user-add" onclick="return false;">Add User <kbd><?php echo getPrice(); ?></kbd></a>
									</li>
								</ul>
							</li>
							<?php
						}
						?>
						<?php
						if (getrole() == 'ADMIN'){
							?>
							<li>
								<a href="#"><i class="fa fa-shield fa-fw"></i> Admin</a>
								<ul class="nav nav-second-level">
									<li>
										<a href="#" id="admin-news" onclick="return false;">News</a>
										<ul id="nul-pb" class="nav nav-third-level">
											<li>
												<a href="#" id="admin-news_add" onclick="return false;">Add News</a>
											</li>
											<li>
												<a href="#" id="admin-news_manage" onclick="return false;">Manage News</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="#" id="user-manage" onclick="return false;">User</a>
										<ul id="nul-pb" class="nav nav-third-level">
											<li>
												<a href="#" id="admin-user-add" onclick="return false;">Add User</a>
											</li>
											<li>
												<a href="#" id="admin-user-manage" onclick="return false;">Manage User</a>
											</li>
										</ul>
									</li>
									<li>
										<a href="#" id="admin-config" onclick="return false;">Config</a>
									</li>
									<li>
										<a href="#" id="admin-logs" onclick="return false;">Logs</a>
									</li>
								</ul>
							</li>
							<?php
						}
						?>
                        <li>
							<a href="#"><i class="fa fa-wrench fa-fw"></i> Settings</a>
							<ul class="nav nav-second-level">
								<li>
									<a href="#" id="setting-changepass" onclick="return false;">Change Password</a>
								</li>
								<li>
									<a href="#" id="setting-changename" onclick="return false;">Change Name</a>
								</li>
							</ul>
						</li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
					<div style="height: 30px;"></div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-usd fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div id="balance" class="huge"><?php echo balance($row['balance']); ?></div>
                                    <div>Balance</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Top up</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				
                <div class="col-lg-3 col-md-3">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-database fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div id="points" class="huge"><?php echo $row['point'];?></div>
                                    <div>Points</div>
                                </div>
                            </div>
                        </div>
                        <a>
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-fw"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				
				<div class="col-lg-3 col-md-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-desktop fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div id="registeredhdsn" class="huge"><?php echo $count;?></div>
                                    <div>Registered HDSN</div>
                                </div>
                            </div>
                        </div>
                        <a href="#" id="pan-reghdsn" onclick="return false;";>
                            <div class="panel-footer">
                                <span class="pull-left">Register New HDSN</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
				
				<div class="col-lg-3 col-md-3">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div id="level" class="huge"><?php echo $role;?></div>
                                    <div>Level</div>
                                </div>
                            </div>
                        </div>
                        <a>
                            <div class="panel-footer">
                                <span class="pull-left"></span>
                                <span class="pull-right"><i class="fa fa-fw"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
            </div>
            <!-- /.row -->
            <div class="row" id="body-pnl">
                <div class="col-lg-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-th-list fa-fw"></i> Main
                        </div>
                        <div class="panel-body" id="pnlBody">	<!-- HERE IS THE PANEL BODY -->
                            <h1>Hello World!</h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tasks fa-fw"></i> Result
							<div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <li><a href="#" id="res-copy" onclick="return false;">Copy</a>
                                        </li>
                                        <li><a href="#" id="res-clear" onclick="return false;">Clear</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
							<textarea class="form-control" id="result" readonly="true" rows="10"></textarea>
                        </div>
                    </div>
                </div>
            </div>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<i class="fa fa-info-circle fa-fw" ></i> Information
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table">
									<tbody id="news-body">
										<?php
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
									</tbody>
								</table>
							</div>
							
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
    <div id="null">
	</div>
</body>
<script>
$("#logout").click(function(){window.location.href = "./logout.php";});
//
$("#pb-reg").click(function(){buka('pages/pb-register');});
$("#pb-change").click(function(){buka('pages/pb-change');});
$("#pb-extend").click(function(){buka('pages/pb-extend');});
$("#pb-check").click(function(){buka('pages/pb-check');});
//
$("#setting-changepass").click(function(){buka('pages/setting-change_password');});
$("#setting-changename").click(function(){buka('pages/setting-change_name');});
//
$("#user-add").click(function(){buka('pages/user-add');})
//
$("#admin-news_add").click(function(){buka('pages/admin-news_add');})
$("#admin-news_manage").click(function(){buka('pages/admin-news_manage');})
//
$("#admin-logs").click(function(){admlog();});
$("#admin-config").click(function(){buka('pages/admin-config');});
$("#admin-user-add").click(function(){buka('pages/admin-user_add');})
$("#admin-user-manage").click(function(){buka('pages/admin-user_manage');})
//
$("#pan-reghdsn").click(function (){
	$("#nul-games").addClass("in");
	$("#nul-pb").addClass("in");
	$('#pb-reg').click();
});
//
$("#res-copy").click(function(){
	var copyText = document.getElementById("result");
	copyText.select();
	document.execCommand("copy");
});

$("#res-clear").click(function(){
	$("#result").empty();
});

function buka(nama) {
	pagenull();
	$("#pnlBody").html('<div class="progress"> <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:100%"> </div> </div>');
	$.ajax({
		url		: './'+ nama +'.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(isi){
			$("#pnlBody").html(isi);
			$("#body-pnl")[0].scrollIntoView({
				behavior: "smooth", // or "auto" or "instant"
				block: "start" // or "end"
			});
			/*$('html, body').animate({
				scrollTop: $("#pnlBody").offset().top
			}, 1000);*/
		},
	});
}
function admlog(){
	$("#body-pnl").html('<div class="progress"> <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:100%"> </div> </div>');
	$.ajax({
		url		: './pages/admin-logs.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(isi){
			$("#body-pnl").html(isi);
			$("#body-pnl")[0].scrollIntoView({
				behavior: "smooth", // or "auto" or "instant"
				block: "start" // or "end"
			});
		},
	});
}
function buka2(nama) {
	//pagenull();
	$.ajax({
		url		: './'+ nama +'.php',
		type	: 'GET',
		dataType: 'html',
		success	: function(isi){
			$("#pnlBody").html(isi);
		},
	});
}
</script>
</html>
