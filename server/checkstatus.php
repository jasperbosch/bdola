<?php
require_once ("models/config.php");
global $mysqli;
$errors = array ();
$data = null;
try {
	$sQuery = "SELECT locatie FROM ch_checkins WHERE user_name = ?";
	
	$stmt = $mysqli->prepare ( $sQuery );
	$stmt->bind_param ( 's', $_SESSION [SESSION_USER]->username );
	$stmt->execute ();
	$stmt->bind_result ( $locatie );
	while ( $stmt->fetch () ) {
		$data = array (
				'locatie' => $locatie 
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