<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}
$errors = array ();

try {
	$sQuery = "SELECT * FROM ch_locaties";
	
	$stmt = $mysqli->prepare ( $sQuery );
	$stmt->execute();
	$stmt->bind_result($id,$naam,$rgb);
	
	while ($stmt->fetch()){
		$data[]=array('id'=>$id,'locatie'=>$naam);
	}
	$stmt->close();
	

} catch ( Exception $e ) {
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