<?php
require_once ("models/checkin_db_config.php");
function getCheckinStatus($username) {
	global $db;
	$errors = array ();
	
	try {
		$sQuery = "SELECT locatie FROM ch_checkins WHERE user_name = :user_name";
		
		$oStmt = $db->prepare ( $sQuery );
		$oStmt->bindParam ( ':user_name', $username, PDO::PARAM_STR );
		$oStmt->execute ();
		
		while ( $aRow = $oStmt->fetch ( PDO::FETCH_ASSOC ) ) {
			return $aRow['locatie'];
		}
		return -1;
		
	} catch ( PDOException $e ) {
		$sMsg = 'Regelnummer: ' . $e->getLine () . '
				Bestand: ' . $e->getFile () . '
				Foutmelding: ' . $e->getMessage ();
		
		trigger_error($sMsg);
	}
	return -1;
}

?>