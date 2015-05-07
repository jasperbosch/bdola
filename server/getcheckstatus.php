<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}
$errors = array ();

$userData = fetchAllUsers();
$data = array();
try {
	
	foreach ($userData as $v1){
		
		$username = $v1['user_name'];
		$displayname = $v1['display_name'];
		
		$sQuery = "SELECT a.user_name, a.phone, b.naam, b.rgb, d.locatie
				FROM ch_preferences a 
				LEFT JOIN ch_teams b 
					ON b.id=a.team
				LEFT JOIN ch_checkins c
					ON c.user_name = ?
				LEFT JOIN ch_locaties d
					ON d.id = c.locatie
				WHERE a.user_name = ?";
	
		$stmt = $mysqli->prepare ( $sQuery );
		$stmt->bind_param ( 'ss', $username, $username);
		$stmt->execute();
		$stmt->bind_result($user,$phone,$naam,$rgb,$locatie);
		
		while ($stmt->fetch()){
			array_push($data,array('displayname'=>$displayname,'phone'=>$phone,'naam'=>$naam,'rgb'=>$rgb,'locatie'=>$locatie));
			break;
		}
		$stmt->close();
	}

} catch ( Exception $e ) {
	$sMsg = 'Regelnummer: ' . $e->getLine () . '
				Bestand: ' . $e->getFile () . '
				Foutmelding: ' . $e->getMessage ();

	$error = new error ();
	$error->type = "danger";
	$error->msg = $sMsg;
	$errors [] = $error;
}
if (count ( $errors ) > 0) {
	$data = array (
			'id' => session_id (),
			'error' => $errors
	);
}
echo json_encode ( $data );

?>