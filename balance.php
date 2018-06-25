<?php
function balance($n, $precision = 3) {
    if ($n < 1000000) {
        // Anything less than a million
        $n_format = number_format($n);
    } else if ($n < 1000000000) {
        // Anything less than a billion
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        // At least a billion
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}

// Shortens a number and attaches K, M, B, etc. accordingly
function balance2($number, $precision = 3, $divisors = null) {

    // Setup default $divisors if not provided
    if (!isset($divisors)) {
        $divisors = array(
            pow(1000, 0) => '', // 1000^0 == 1
            pow(1000, 1) => 'K', // Thousand
            pow(1000, 2) => 'M', // Million
            pow(1000, 3) => 'B', // Billion
            pow(1000, 4) => 'T', // Trillion
            pow(1000, 5) => 'Qa', // Quadrillion
            pow(1000, 6) => 'Qi', // Quintillion
        );    
    }

    // Loop through each $divisor and find the
    // lowest amount that matches
    foreach ($divisors as $divisor => $shorthand) {
        if (abs($number) < ($divisor * 1000)) {
            // We found a match!
            break;
        }
    }

    // We found our match, or there were no matches.
    // Either way, use the last defined value for $divisor.
    return number_format($number / $divisor, $precision) . $shorthand;
}

function balance3($number)
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

function balance4( $n, $precision = 1 ) {
    if ($n < 900) {
        // 0 - 900
        $n_format = number_format($n, $precision);
        $suffix = '';
    } else if ($n < 900000) {
        // 0.9k-850k
        $n_format = number_format($n / 1000, $precision);
        $suffix = 'K';
    } else if ($n < 900000000) {
        // 0.9m-850m
        $n_format = number_format($n / 1000000, $precision);
        $suffix = 'M';
    } else if ($n < 900000000000) {
        // 0.9b-850b
        $n_format = number_format($n / 1000000000, $precision);
        $suffix = 'B';
    } else {
        // 0.9t+
        $n_format = number_format($n / 1000000000000, $precision);
        $suffix = 'T';
    }
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
    if ( $precision > 0 ) {
        $dotzero = '.' . str_repeat( '0', $precision );
        $n_format = str_replace( $dotzero, '', $n_format );
    }
    return $n_format . $suffix;
}

echo balance (54970000);
echo '<br>';
echo balance2(54970000);
echo '<br>';
echo balance3(54970000);
echo '<br>';
echo balance4(54970000);


////////////////////
date_default_timezone_set('Asia/Jakarta');
echo '<br>';
$stop_date = new DateTime('now');
echo 'date before day adding: ' . $stop_date->format('d-m-Y'); 
echo '<br>';
$stop_date->modify('+30 day');
echo 'date after adding 1 day: ' . $stop_date->format('d-m-Y');
////
echo $_SERVER['REQUEST_METHOD'];
///
echo '<br>';
function dayCount($from, $to) {
    $first_date = strtotime($from);
    $second_date = strtotime($to);
    $offset = $second_date-$first_date; 
    return floor($offset/60/60/24);
}
echo dayCount('11-12-2018', '12-12-2018');
echo strtotime('now');

////
echo '<br>';echo '<br>';echo '<br>';
$con = mysqli_connect('localhost', 'root', '', 'dbproj');
$q = mysqli_query($con, "SELECT * FROM prices WHERE 1");
$r = mysqli_fetch_array($q);
echo $r['user-register'];
echo '<br>';
$xxss = mt_rand(1, 1000);
echo $xxss;

//
$homepage = file_get_contents('http://ipv4bot.whatismyipaddress.com/');
echo $homepage;
?>