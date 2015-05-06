<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}
define ( 'SESSION_MONTHYEAR', 'monthYear' );
$postdata = file_get_contents ( "php://input" );

$data = array ();
$prefs = array ();
$errors = array ();
$username = trim ( $_SESSION [SESSION_USER]->username );

$sQuery = "SELECT * FROM ch_preferences WHERE user_name = ?";

$stmt = $mysqli->prepare ( $sQuery );
$stmt->bind_param ( 's', $username );
$stmt->execute ();
$stmt->bind_result ( $username, $phone, $team, $mo, $tu, $we, $th, $vr, $sa, $su, $timestamp );
while ( $stmt->fetch () ) {
	$prefs [1] = $mo;
	$prefs [2] = $tu;
	$prefs [3] = $we;
	$prefs [4] = $th;
	$prefs [5] = $vr;
	$prefs [6] = $sa;
	$prefs [7] = $su;
}

$werkDatum = getCurrentDate ();
if ($postdata == 0) {
	$monthYear = $werkDatum;
} elseif ($postdata == - 1) {
	$monthYear = $_SESSION [SESSION_MONTHYEAR];
	$monthInterval = new DateInterval ( 'P1M' );
	$monthYear->sub ( $monthInterval );
} elseif ($postdata == 1) {
	$monthYear = $_SESSION [SESSION_MONTHYEAR];
	$monthInterval = new DateInterval ( 'P1M' );
	$monthYear->add ( $monthInterval );
}
$_SESSION [SESSION_MONTHYEAR] = $monthYear;

$datum = getFistDayOfCalender ( $monthYear );
$datumParam = $datum->format ( 'Y-m-d' );
$clone = clone $datum;
$datumTotParam = $clone->add ( new DateInterval ( 'P1M7D' ) )->format ( 'Y-m-d' ) ;

$sQuery = "SELECT * FROM ch_data WHERE user_name = ? AND datum BETWEEN ? AND ?";
$stmt = $mysqli->prepare ( $sQuery );
$stmt->bind_param ( 'sss', $username, $datumParam, $datumTotParam);
$stmt->execute ();
$stmt->bind_result ( $username, $datumValue, $soort, $uren, $timestamp );
$chdata = array ();
while ( $stmt->fetch () ) {
	$chdata [$datumValue] = array (
			'soort' => $soort,
			'uren' => $uren 
	);
}

$interval = new DateInterval ( 'P1D' );
while ( datumIsInDezeMaand ( $datum, $monthYear ) ) {
	$week = array ();
	for($i = 0; $i < 7; $i ++) {
		$dag = new stdClass ();
		$dag->datum = $datum->format ( 'Y-m-d' );
		$dag->dag = $datum->format ( 'j' );
		$dag->maand = intval ( $datum->format ( 'n' ) );
		$dag->dow = intval ( $datum->format ( 'N' ) );
		if (isset ( $chdata [$dag->datum] )) {
			$dag->uren = ( float ) $chdata[$dag->datum]['uren'];
			$dag->soort = $chdata[$dag->datum]['soort'];
		} else {
			$dag->uren = ( float ) $prefs [$dag->dow];
			if ($dag->uren == 0) {
				$dag->soort = 'V';
			} else {
				$dag->soort = 'K';
			}
		}
		$week [] = $dag;
		$datum->add ( $interval );
	}
	$weeks [] = $week;
}
$data = array (
		'weeks' => $weeks,
		'currmonth' => intval ( $monthYear->format ( 'n' ) ),
		'currmonthname' => getFullMonth ( $monthYear ) 
);

if (count ( $errors ) > 0) {
	$data = array (
			'id' => session_id (),
			'error' => $errors 
	);
}
echo json_encode ( $data );
function getCurrentDate() {
	return new DateTime ();
}
function getCurrentMonth() {
	$date = new DateTime ();
	return intval ( $date->format ( "n" ) );
}
function getFullMonth($date) {
	return $date->format ( "F Y" );
}
function getFirstDayOfMonth($datum) {
	return date ( "N", strtotime ( $datum . "-01" ) );
}
function getFistDayOfCalender($datum) {
	$dag = intval ( getFirstDayOfMonth ( $datum->format ( 'Y-m' ) ) ) + 3;
	// echo var_dump($dag);
	// if ($dag==0){
	// $dag=7;
	// }
	$clone = clone $datum;
	$clone->sub ( new DateInterval ( 'P' . $dag . 'D' ) );
	return $clone;
}
function datumIsInDezeMaand($datum, $werkDatum) {
	return $datum->format ( 'Ym' ) <= $werkDatum->format ( 'Ym' );
}