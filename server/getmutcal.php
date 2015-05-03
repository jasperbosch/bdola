<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}
define ( 'SESSION_MONTHYEAR', 'monthYear' );
$postdata = file_get_contents ( "php://input" );


$data = array ();
$errors = array ();
$username = trim ( $_SESSION [SESSION_USER]->username );

$werkDatum = getCurrentDate ();
if ($postdata == 0) {
	$monthYear = $werkDatum;
} elseif($postdata == -1){
	$monthYear = $_SESSION[SESSION_MONTHYEAR];
	$monthInterval = new DateInterval ('P1M');
	$monthYear->sub($monthInterval);
} elseif ($postdata==1){
	$monthYear = $_SESSION[SESSION_MONTHYEAR];
	$monthInterval = new DateInterval ('P1M');
	$monthYear->add($monthInterval);
}
$_SESSION [SESSION_MONTHYEAR] = $monthYear;

$datum = getFistDayOfCalender ( $monthYear );
$interval = new DateInterval ( 'P1D' );
while ( datumIsInDezeMaand ( $datum, $monthYear ) ) {
	$week = array ();
	for($i = 0; $i < 7; $i ++) {
		$dag = new stdClass ();
		$dag->datum = $datum->format ( 'Y-m-d' );
		$dag->dag = $datum->format ( 'j' );
		$dag->maand = intval($datum->format ( 'n' ));
		$dag->dow = $datum->format ( 'N' );
		$week [] = $dag;
		$datum->add ( $interval );
	}
	$weeks [] = $week;
}
$data = array (
		'weeks' => $weeks,
		'currmonth' => intval($monthYear->format('n')),
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
	return intval( $date->format ( "n" ));
}
function getFullMonth($date) {
	return $date->format ( "F Y" );
}
function getFirstDayOfMonth($datum) {
	return date ( "N", strtotime ( $datum . "-01" ) );
}
function getFistDayOfCalender($datum) {
	$dag = intval(getFirstDayOfMonth ( $datum->format ( 'Y-m' ) )) + 1;
// 	echo var_dump($dag);
// 	if ($dag==0){
// 		$dag=7;
// 	}
	$clone = clone $datum;
	$clone->sub ( new DateInterval ( 'P' . $dag . 'D' ) );
	return $clone;
}
function datumIsInDezeMaand($datum, $werkDatum) {
	return $datum->format ( 'Ym' ) <= $werkDatum->format ( 'Ym' );
}