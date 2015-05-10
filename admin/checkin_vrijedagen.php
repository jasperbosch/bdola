<?php
/*
 * UserCake Version: 2.0.2
 * http://usercake.com
 */
require_once ("models/config.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}

// Forms posted
if (! empty ( $_POST )) {
	if (! empty ( $_POST ['delete'] )) {
		$deletions = $_POST ['delete'];
		if ($deletion_count = deleteVrijedagens ( $deletions )) {
			$successes [] = lang ( "VRIJEDAGEN_DELETIONS_SUCCESSFUL", array (
					$deletion_count 
			) );
		} else {
			$errors [] = lang ( "SQL_ERROR" );
		}
	}
	
	// Create new permission level
	if (! empty ( $_POST ['newVrijedagen'] )) {
		$vrijedagen = trim ( $_POST ['newVrijedagen'] );
		
		// Validate request
		if (vrijedagenNameExists ( $vrijedagen )) {
			$errors [] = lang ( "VRIJEDAGEN_NAME_IN_USE", array (
					$vrijedagen 
			) );
		} elseif (minMaxRange ( 1, 50, $vrijedagen )) {
			$errors [] = lang ( "VRIJEDAGEN_CHAR_LIMIT", array (
					1,
					50 
			) );
		} else {
			if (createVrijedagen ( $vrijedagen )) {
				$successes [] = lang ( "VRIJEDAGEN_CREATION_SUCCESSFUL", array (
						$vrijedagen 
				) );
			} else {
				$errors [] = lang ( "SQL_ERROR" );
			}
		}
	}
}

$userData = fetchAllVrijedagens (); // Fetch information for all vrijedagens

require_once ("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Checkin Vrijedagens</h2>
<div id='left-nav'>";

include ("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock ( $errors, $successes );

echo "
<form name='checkinVrijedagens' action='" . $_SERVER ['PHP_SELF'] . "' method='post'>
<table class='admin'>
<tr>
<th>Delete</th><th>Vrijedagen</th>
</tr>";

// Cycle through users
foreach ( $userData as $v1 ) {
	echo "
	<tr>
	<td><input type='checkbox' name='delete[" . $v1 ['datum'] . "]' id='delete[" . $v1 ['datum'] . "]' value='" . $v1 ['datum'] . "'></td>
	<td>".$v1['datum']."</td>
	</tr>";
}

echo "
</table>
<p>
<label>Vrijedag:</label>
<input type='text' name='newVrijedagen' placeholder='jjjj-mm-dd'/>
</p>                                
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
