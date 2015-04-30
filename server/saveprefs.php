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
	$username = trim ( $_SESSION [SESSION_USER]->username );
	$phone = trim ( $request->phone );
	$team =  $request->team ;
	
	try {
		$sQuery = "SELECT count(*) FROM ch_preferences WHERE user_name = :user_name";
		
		$oStmt = $db->prepare ( $sQuery );
		$oStmt->bindParam ( ':user_name', $username, PDO::PARAM_STR );
		$oStmt->execute ();
		
		$result = - 1;
		while ( $aRow = $oStmt->fetch ( PDO::FETCH_ASSOC ) ) {
			$result = 0;
		}
		
		if ($result < 0) {
			// Er was nog geen record, dus INSERT
			$sQuery = "INSERT INTO ch_preferences (
            	user_name,
				phone,
				team
        	) VALUES (
            	:user_name,
				:phone,
				:team
        	)";
		} else {
			// Er was al wel een record, dus UPDATE
			$sQuery = "UPDATE ch_preferences SET
				phone = :phone,
				team = :team
        	WHERE user_name = :user_name";
		}

		$oStmt = $db->prepare ( $sQuery );
		$oStmt->bindParam ( ':user_name', $username, PDO::PARAM_STR );
		$oStmt->bindParam ( ':phone', $phone, PDO::PARAM_STR );
		$oStmt->bindParam ( ':team', $team, PDO::PARAM_INT );
		$oStmt->execute ();
		
		$data = array (
				'id' => session_id (),
				'data' => "OK"
		);
		
	} catch ( PDOException $e ) {
		$sMsg = 'Regelnummer: ' . $e->getLine () . '
		Bestand: ' . $e->getFile () . '
		Foutmelding: ' . $e->getMessage () ;
		
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