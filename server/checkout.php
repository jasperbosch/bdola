<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}

$postdata = file_get_contents ( "php://input" );
$request = json_decode ( $postdata );

// Forms posted
if (! empty ( $request )) {
	$errors = array ();
	$check = checkSession ();
	if ($check != "OK") {
		$errors [] = $check;
	} else {
		$username = trim ( $_SESSION [SESSION_USER]->username );
		
		try {
			$sQuery = "DELETE FROM ch_checkins WHERE user_name = ?";
			
			$stmt = $mysqli->prepare ( $sQuery );
			$stmt->bind_param ( 's', $username );
			$stmt->execute ();
			$stmt->close ();
		} catch ( Exception $e ) {
			$sMsg = 'Regelnummer: ' . $e->getLine () . '
				Bestand: ' . $e->getFile () . '
				Foutmelding: ' . $e->getMessage ();
			
			$error = new error ();
			$error->type = "danger";
			$error->msg = $sMsg;
			$errors [] = $error;
		}
	}
}
if (count ( $errors ) > 0) {
	$data = array (
			'id' => session_id (),
			'error' => $errors 
	);
	echo json_encode ( $data );
}

?>