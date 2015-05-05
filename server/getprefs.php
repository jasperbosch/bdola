<?php
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}

$data = NULL;
$errors = array ();
$username = trim ( $_SESSION [SESSION_USER]->username );
setlocale(LC_ALL, 'nl_NL');

try {
	$sQuery = "SELECT * FROM ch_preferences WHERE user_name = ?";
	
	$stmt = $mysqli->prepare ( $sQuery );
	$stmt->bind_param ( 's', $username );
	$stmt->execute ();
	$stmt->bind_result($username,$phone,$team,$mo, $tu, $we, $th, $vr, $sa, $su, $timestamp);
	while ($stmt->fetch()){
		$data = array('user_name'=>$username,
				'phone'=>$phone,'team'=>$team,
				'mo'=>(float)$mo, 'tu'=>(float)$tu, 'we'=>(float)$we, 'th'=>(float)$th, 'vr'=>(float)$vr, 'sa'=>(float)$sa, 'su'=>(float)$su
		);
	}
	
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

function format($number){
	return number_format($number, 1, ',','');
}

?>