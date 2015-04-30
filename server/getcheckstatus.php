<?php
require_once ("models/config.php");
require_once ("checkstatus.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}
global $db;
$errors = array ();

$userData = fetchAllUsers();
$data = array();
try {
	
	foreach ($userData as $v1){
		
		$username = $v1['user_name'];
		
		$sQuery = "SELECT a.user_name, a.phone, b.naam, b.rgb, c.locatie
				FROM ch_preferences a 
				LEFT JOIN ch_teams b 
					ON b.id=a.team
				LEFT JOIN ch_checkins c
					ON c.user_name = a.user_name
				WHERE a.user_name = :username";
	
		$oStmt = $db->prepare ( $sQuery );
		$oStmt->bindParam ( ':username', $username, PDO::PARAM_STR );
		$oStmt->execute();
	
		array_push($data,$oStmt->fetch ( PDO::FETCH_OBJ ));
	}

} catch ( PDOException $e ) {
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