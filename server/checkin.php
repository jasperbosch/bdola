<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}

$data = NULL;
$postdata = file_get_contents ( "php://input" );
$request = json_decode ( $postdata );

// Forms posted
if (! empty ( $request )) {
	$errors = array ();
	$username = trim ( $_SESSION [SESSION_USER]->username );
	$locatie = trim ( $request->location );
	
	try {
		$sQuery = " 
        INSERT INTO ch_checkins 
        ( 
            user_name,locatie 
        ) 
        VALUES 
        ( 
            ?,? 
        ) 
    ";
		
		$stmt = $mysqli->prepare ( $sQuery );
		$stmt->bind_param ( 'si', $username, $locatie );
		$stmt->execute ();
		$stmt->close();
		
		$sQuery = "SELECT locatie
				FROM ch_locaties 
				WHERE id = ?";
		
		$stmt = $mysqli->prepare ( $sQuery );
		$stmt->bind_param ( 'i', $locatie );
		$stmt->execute ();
		$stmt->bind_result ( $locatienaam);
		while ( $stmt->fetch () ) {
			$data = array (
					'locatie' => $locatienaam,
			);
		}
		$stmt->close ();
		
	} catch ( Exception $e ) {
		// Geen foutmelding om duplicate te voorkomen.
		
		// $sMsg = 'Regelnummer: ' . $e->getLine () . '
		// Bestand: ' . $e->getFile () . '
		// Foutmelding: ' . $e->getMessage () ;
		
		// $error = new error ();
		// $error->type = "danger";
		// $error->msg = $sMsg;
		// $errors [] = $error;
	}
}
if (count ( $errors ) > 0) {
	$data = array (
			'id' => session_id (),
			'error' => $errors 
	);
}
echo json_encode ( $data );

?>