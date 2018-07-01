<?php
require_once('db.php');
date_default_timezone_set("Asia/Jakarta");
function balance($number)
{
    $abbrevs = [12 => 'T', 9 => 'B', 6 => 'M', 3 => 'K', 0 => ''];

    foreach ($abbrevs as $exponent => $abbrev) {
        if (abs($number) >= pow(10, $exponent)) {
            $display = $number / pow(10, $exponent);
            $decimals = ($exponent >= 3 && round($display) < 100) ? 1 : 0;
            $number = number_format($display, $decimals).$abbrev;
            break;
        }
    }

    return $number;
}
function gettitle(){
	global $con;
	$q = mysqli_query($con, "SELECT * FROM config");
	$r = mysqli_fetch_array($q);
	$res = $r['title'];
	return $res;
}
function headpage($title){
	?>
	<?php
	$_title;
	if (isset($title)){
		$_title = ' - '.$title;
	}else{
		$_title = '';
	}
	?>
	<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo gettitle(); echo $_title; ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="./vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="./vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
	
    <!-- DataTables CSS -->
    <link href="../vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
	
    <!-- Custom CSS -->
    <link href="./dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="./vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!--   SCRIPT   -->
	
    <!-- jQuery -->
    <script src="./vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="./vendor/metisMenu/metisMenu.min.js"></script>
	
	<!-- DataTables JavaScript -->
	
    <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="./dist/js/sb-admin-2.js"></script>
	</head>
	<?php
}

function getrole(){
	//session_start();
	$username = $_SESSION['username'];
	global $con;
	$query = mysqli_query($con, "SELECT * FROM users WHERE username = '$username'");
	$res = mysqli_fetch_array($query);
	$out = $res['role'];
	return $out;
}

function dayCount($from, $to) {
    $first_date = strtotime($from);
    $second_date = strtotime($to);
    $offset = $second_date-$first_date; 
    return floor($offset/60/60/24);
}

function dayAdd($from, $days){
	$stop_date = new DateTime($from);
	//echo 'date before day adding: ' . $stop_date->format('d-m-Y'); 
	//echo '<br>';
	$stop_date->modify('+'.$days.' day');
	//echo 'date after adding 1 day: ' . $stop_date->format('d-m-Y');
	return $stop_date->format('d-m-Y');
}

function dateNow(){
	$dateNow = new DateTime('now'); $dateNow = $dateNow -> format('d-m-Y');
	return $dateNow;
}

function userlogs($username, $action, $info){
	global $con;
	mysqli_query($con, "INSERT INTO logs (username, date, action, info) VALUES ('$username', '".dateNow()." ".Date('H:i:s')."', '$action', '$info')");
}

function notice($type, $info){
	$randomnum = mt_rand(1, 1000);
	?>
	<div class="modal fade" id="overlay<?php echo $randomnum; ?>">
		  <div class="modal-dialog">
			<div style="padding-top: 5px;">
				<div class="alert alert-<?php echo $type;?>" style="margin: 0 auto;">
					<?php echo $info; ?>
				</div>
			</div>
		 </div>
	</div>
	<script>
		$('#overlay<?php echo $randomnum; ?>').modal('show');
			
		setTimeout(function() {
			$('#overlay<?php echo $randomnum; ?>').modal('hide');
		}, 2000);
	</script>
	<?php
}

function currentBalance($username){
	global $con;
	$query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	$dat = mysqli_fetch_array($query);
	
	$currentBalance = $dat['balance'];
	return $currentBalance;
}

function getIP(){
	$r = file_get_contents('http://ipv4bot.whatismyipaddress.com/');
	return $r;
}

function getPrice(){
	global $con;
	$query = mysqli_query($con, "SELECT * FROM config");
	$row = mysqli_fetch_array($query);
	return $row['pricereg'];
}

function creditBalance($username, $price, $points){
	global $con;
	$newBalance = currentBalance($username) - $price;
	$query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	$dat = mysqli_fetch_array($query);
	$curpoint = $dat['point'] + $points;
	//echo $curpoint;
	//echo $dat['point'];
	//$curPoint = $res['point'] + 5;
	mysqli_query($con, "UPDATE users SET point='$curpoint' WHERE username='$username'");
	mysqli_query($con, "UPDATE users SET balance='$newBalance' WHERE username='$username'");
}
?>