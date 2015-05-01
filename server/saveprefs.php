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
	$phone = trim ( $request->phone );
	$team = $request->team;
	
	try {
		$sQuery = "SELECT count(*) FROM ch_preferences WHERE user_name = ?";
		
		$stmt = $mysqli->prepare ( $sQuery );
		$stmt->bind_param ( 's', $username );
		$stmt->execute ();
		$stmt->store_result ();
		$num_returns = $stmt->num_rows;
		$stmt->close ();
		
		$result = - 1;
		if ($num_returns > 0) {
			$result = 0;
		}
		
		if ($result < 0) {
			// Er was nog geen record, dus INSERT
			$sQuery = "INSERT INTO ch_preferences (
            	user_name,phone,team
        	) VALUES (
            	?,?,?
        	)";
			$stmt = $mysqli->prepare ( $sQuery );
			$stmt->bind_param ( 'sss', $username, $phone, $team );
		} else {
			// Er was al wel een record, dus UPDATE
			$sQuery = "UPDATE ch_preferences SET
				phone = ?,
				team = ?
        	WHERE user_name = ?";
			$stmt = $mysqli->prepare ( $sQuery );
			$stmt->bind_param ( 'sss', $phone, $team, $username );
		}
		
		$stmt->execute ();
		
		$data = array (
				'id' => session_id (),
				'data' => "OK" 
		);
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
if (count ( $errors ) > 0) {
	$data = array (
			'id' => session_id (),
			'error' => $errors 
	);
}
echo json_encode ( $data );

?>