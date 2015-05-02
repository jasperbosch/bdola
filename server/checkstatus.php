<?php
require_once ("models/config.php");
global $mysqli;
$errors = array ();
$data = null;
try {
	$sQuery = "SELECT a.locatie, b.locatie as locatienaam
				FROM ch_checkins a
				LEFT JOIN ch_locaties b
					ON b.id = a.locatie  
				WHERE a.user_name = ?";
	
	$stmt = $mysqli->prepare ( $sQuery );
	$stmt->bind_param ( 's', $_SESSION [SESSION_USER]->username );
	$stmt->execute ();
	$stmt->bind_result ( $locatie , $locatienaam);
	while ( $stmt->fetch () ) {
		$data = array (
				'locatie' => $locatie,
				'locatienaam' => $locatienaam 
		);
	}
	$stmt->close ();
} catch ( Exception $e ) {
	$sMsg = 'Regelnummer: ' . $e->getLine () . '
				Bestand: ' . $e->getFile () . '
				Foutmelding: ' . $e->getMessage ();
	
	trigger_error ( $sMsg );
}
if (count ( $errors ) > 0) {
	$data = array (
			'id' => session_id (),
			'error' => $errors 
	);
}
echo json_encode ( $data );

?>