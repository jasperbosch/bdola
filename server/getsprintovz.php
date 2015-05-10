<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}
define ( 'SESSION_MONTHYEAR', 'monthYear' );
$postdata = file_get_contents ( "php://input" );

	$currentDate = getCurrentDate ()->format ( 'Y-m-d' );
if (trim($postdata)!=""){
	$currentDate = $postdata;
}

// Bepaal de huidige sprint
$sQuery = "SELECT naam, datum FROM ch_sprints WHERE datum <= ? ORDER BY datum DESC LIMIT 1";

$stmt = $mysqli->prepare ( $sQuery );
$stmt->bind_param ( 's', $currentDate );
$stmt->execute ();
$stmt->bind_result ( $naam, $datum );
$currentSprint = array ();
while ( $stmt->fetch () ) {
	$currentSprint = array (
			'naam' => $naam,
			'datum' => $datum 
	);
}
$stmt->close ();

// Bepaal de vorige sprint
$sQuery = "SELECT naam, datum FROM ch_sprints WHERE datum < ? ORDER BY datum DESC LIMIT 1";

$stmt = $mysqli->prepare ( $sQuery );
$stmt->bind_param ( 's', $currentSprint ['datum'] );
$stmt->execute ();
$stmt->bind_result ( $naam, $datum );
$prevSprint = array ();
while ( $stmt->fetch () ) {
	$prevSprint = array (
			'naam' => $naam,
			'datum' => $datum 
	);
}
$stmt->close ();

// Bepaal de volgende sprint
$sQuery = "SELECT naam, datum FROM ch_sprints WHERE datum > ? ORDER BY datum ASC LIMIT 1";

$stmt = $mysqli->prepare ( $sQuery );
$stmt->bind_param ( 's', $currentSprint ['datum'] );
$stmt->execute ();
$stmt->bind_result ( $naam, $datum );
$nextSprint = array ();
while ( $stmt->fetch () ) {
	$nextSprint = array (
			'naam' => $naam,
			'datum' => $datum 
	);
}
$stmt->close ();

$startDate = new DateTime ( $currentSprint ['datum'] );
$endDate = new DateTime ( $nextSprint ['datum'] );

// Bepaal alle afwijkende datums
$sQuery = "SELECT datum, user_name, soort, uren
		FROM ch_data
		WHERE datum BETWEEN ? AND ?";

$stmt = $mysqli->prepare ( $sQuery );
$stmt->bind_param ( 'ss', $currentSprint ['datum'], $nextSprint ['datum'] );
$stmt->execute ();
$stmt->bind_result ( $datum, $userName, $soort, $uren );
$afwdata = array ();
while ( $stmt->fetch () ) {
	$afw = new stdClass ();
	$afw->soort = $soort;
	$afw->uren = $uren;
	$afwdata [$datum] [$userName] = $afw;
}
$stmt->close ();

// Bepaal de users
$sQuery = "SELECT a.user_name, a.display_name, c.naam, c.rgb, b.mo, b.tu, b.we, b.th, b.vr, b.sa, b.su, b.functie
		FROM uc_users a
		LEFT JOIN ch_preferences b
			ON b.user_name = a.user_name
		LEFT JOIN ch_teams c
			ON c.id = b.team
		ORDER BY a.display_name ASC";

$stmt = $mysqli->prepare ( $sQuery );
$stmt->execute ();
$stmt->bind_result ( $userId, $userName, $team, $rgb, $ma, $tu, $we, $th, $vr, $sa, $su, $functie );
$users = array ();

$users = array ();
$totAanwezig = array ();
$totTeam = array ();
while ( $stmt->fetch () ) {
	$prefs = array (
			'ma' => $ma,
			'tu' => $tu,
			'we' => $we,
			'th' => $th,
			'vr' => $vr,
			'sa' => $sa,
			'su' => $su 
	);
	$user = array (
			'id' => $userId,
			'naam' => $userName,
			'team' => $team,
			'rgb' => $rgb,
			'data' => getData ( $startDate, $endDate, $prefs, $afwdata, $userId, $functie, $team, $rgb ) 
	);
	$users [] = $user;
}
$stmt->close ();

$data = array (
		'prevSprint' => $prevSprint,
		'currSprint' => $currentSprint,
		'nextSprint' => $nextSprint,
		'totaal' => $totAanwezig,
		'totaalTeam' => $totTeam,
		'users' => $users 
);

echo json_encode ( $data );
function getData($startDate, $endDate, $prefs, $afwdata, $userName, $functie, $team, $rgb) {
	global $totAanwezig, $totTeam;
	$interval = new DateInterval ( 'P1D' );
	$datum = clone $startDate;
	$data = array ();
	while ( $datum < $endDate ) {
		$dag = new stdClass ();
		$dag->datum = $datum->format ( 'Y-m-d' );
		$dag->dag = $datum->format ( 'j' );
		$dag->maand = intval ( $datum->format ( 'n' ) );
		$dag->dow = intval ( $datum->format ( 'N' ) );
		$dag->dowName = strftime ( '%a', strtotime ( $datum->format ( "d-m-Y" ) ) );
		$dag->soort = 'K';
		if ($datum == $startDate) {
			// Sprintstartdag
			$dag->soort = 'S';
		}
		switch ($dag->dow) {
			case 1 :
				$dag->uren = $prefs ['ma'];
				break;
			case 2 :
				$dag->uren = $prefs ['tu'];
				break;
			case 3 :
				$dag->uren = $prefs ['we'];
				break;
			case 4 :
				$dag->uren = $prefs ['th'];
				break;
			case 5 :
				$dag->uren = $prefs ['vr'];
				break;
			case 6 :
				$dag->uren = $prefs ['sa'];
				break;
			case 7 :
				$dag->uren = $prefs ['su'];
				break;
		}
		
		if (isset ( $afwdata [$dag->datum] [$userName] )) {
			$dag->soort = $afwdata [$dag->datum] [$userName]->soort;
			$dag->uren = $afwdata [$dag->datum] [$userName]->uren;
		}
		if ($dag->soort == "K" && $dag->uren == 0) {
			$dag->soort = "V";
		}
		
		// Totaal aantal bezette plekken per dag
		if (! isset ( $totAanwezig [$dag->datum] )) {
			$totAanwezig [$dag->datum] = 0;
		}
		if ($dag->soort == "K") {
			$totAanwezig [$dag->datum] ++;
		}
		
		// Totaal per team per functie
		if (isset ( $team )) {
			if (! isset ( $totTeam [$team] )) {
				$teamObj = array();
				$teamObj['naam'] = $team;
				$teamObj['rgb'] = $rgb;
				$teamObj[$functie] = 0;
				$totTeam [$team] = $teamObj;
			}
			if (! isset($totTeam [$team] [$functie])){
				$totTeam [$team] [$functie]=0;
			}
			$totTeam [$team] [$functie] = $totTeam [$team] [$functie] + $dag->uren;
		}
		
		// Bepaal de volgende datum
		$data [$datum->format ( 'Y-m-d' )] = $dag;
		$datum->add ( $interval );
	}
	return $data;
}
function getCurrentDate() {
	return new DateTime ();
}
