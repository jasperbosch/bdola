<?php
require_once("db-settings.php");

// try {
	$db = new PDO ( 'mysqli:host=' . $db_host . ';dbname=' . $db_name, $db_user, $db_pass );
	$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$db->query ( "SET SESSION sql_mode = 'ANSI,ONLY_FULL_GROUP_BY'" );
// } catch ( PDOException $e ) {
// 	$sMsg = '<p>
//             Regelnummer: ' . $e->getLine () . '<br />
//             Bestand: ' . $e->getFile () . '<br />
//             Foutmelding: ' . $e->getMessage () . '
//         </p>';
	
// 	$error = new error ();
// 	$error->type = "danger";
// 	$error->msg = $sMsg;
// 	$errors [] = $error;

// 	trigger_error ( $sMsg );
// }


define('SESSION_USER','userCakeUser');
?>