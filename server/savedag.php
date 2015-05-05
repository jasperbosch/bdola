<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}

$data = NULL;
$postdata = file_get_contents ( "php://input" );
$request = json_decode ( $postdata );
setlocale ( LC_ALL, 'nl_NL' );

// Forms posted
if (! empty ( $request )) {
	$errors = array ();
	$username = trim ( $_SESSION [SESSION_USER]->username );
	$soort = $request->soort;
	$uren = $request->uren;
	$datum = $request->datum;
	
	try {
		$sQuery = "SELECT * FROM ch_data WHERE user_name = ? and datum=?";
		
		$stmt = $mysqli->prepare ( $sQuery );
		$stmt->bind_param ( 'ss', $username, $datum );
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
			$sQuery = "INSERT INTO ch_data (
            	user_name,datum,soort,uren
        	) VALUES (
            	?,?,?,?
        	)";
			$stmt = $mysqli->prepare ( $sQuery );
			$stmt->bind_param ( 'sssd', $username, $datum, $soort, $uren );
		} else {
			// Er was al wel een record, dus UPDATE
			$sQuery = "UPDATE ch_data SET
				soort = ?,
				uren = ?
        	WHERE user_name = ? and datum = ?";
			$stmt = $mysqli->prepare ( $sQuery );
			$stmt->bind_param ( 'sdss', $soort, $uren, $username, $datum );
		}
		
		$stmt->execute ();
		
		$errors[]=$stmt->error;
		
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