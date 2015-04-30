<?php
$dbVars = array (
		'host' => 'localhost',
		'dbname' => 'checkin',
		'user' => 'admin8QMKiyh',
		'pass' => 'DDDhUMAEzx7Q'
);

try {
	$db = new PDO ( 'mysql:host=' . $dbVars ['host'] . ';dbname=' . $dbVars ['dbname'], $dbVars ['user'], $dbVars ['pass'] );
	$db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$db->query ( "SET SESSION sql_mode = 'ANSI,ONLY_FULL_GROUP_BY'" );
} catch ( PDOException $e ) {
	$sMsg = '<p>
            Regelnummer: ' . $e->getLine () . '<br />
            Bestand: ' . $e->getFile () . '<br />
            Foutmelding: ' . $e->getMessage () . '
        </p>';
	
	$error = new error ();
	$error->type = "danger";
	$error->msg = $sMsg;
	$errors [] = $error;

	trigger_error ( $sMsg );
}


define('SESSION_USER','userCakeUser');
?>