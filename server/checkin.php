<?php
require_once ("models/config.php");
require_once ("models/checkin_db_config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}

$data = NULL;
$postdata = file_get_contents ( "php://input" );
$request = json_decode ( $postdata );

// Forms posted
if (! empty ( $request )) {
	$errors = array ();
	$username = trim ( $request->userid );
	$locatie = trim ( $request->locatie );
	
	try {
		$sQuery = " 
        INSERT INTO ch_checkins 
        ( 
            user_name,
			locatie 
        ) 
        VALUES 
        ( 
            :user_name,
			:locatie 
        ) 
    ";
		
		$oStmt = $db->prepare ( $sQuery );
		$oStmt->bindParam ( ':user_name', $username, PDO::PARAM_STR );
		$oStmt->bindParam ( ':locatie', $locatie, PDO::PARAM_STR );
		$oStmt->execute ();
	} catch ( PDOException $e ) {
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