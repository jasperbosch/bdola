<?php
require_once ("models/checkin_db_config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}
$errors = array ();

var_dump($db);
die();

try {
	$sQuery = "SELECT * FROM ch_teams";
	
	$oStmt = $db->prepare ( $sQuery );
	$oStmt->execute();
	
	$data=$oStmt->fetchAll ( PDO::FETCH_OBJ );

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