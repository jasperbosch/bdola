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
		if ($deletion_count = deleteLocations ( $deletions )) {
			$successes [] = lang ( "LOCATION_DELETIONS_SUCCESSFUL", array (
					$deletion_count 
			) );
		} else {
			$errors [] = lang ( "SQL_ERROR" );
		}
	}
	
	// Create new permission level
	if (! empty ( $_POST ['newLocation'] )) {
		$location = trim ( $_POST ['newLocation'] );
		
		// Validate request
		if (locationNameExists ( $location )) {
			$errors [] = lang ( "LOCATION_NAME_IN_USE", array (
					$location 
			) );
		} elseif (minMaxRange ( 1, 50, $location )) {
			$errors [] = lang ( "LOCATION_CHAR_LIMIT", array (
					1,
					50 
			) );
		} else {
			if (createLocation ( $location )) {
				$successes [] = lang ( "LOCATION_CREATION_SUCCESSFUL", array (
						$location 
				) );
			} else {
				$errors [] = lang ( "SQL_ERROR" );
			}
		}
	}
}

$userData = fetchAllLocaties (); // Fetch information for all locaties

require_once ("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Checkin Locaties</h2>
<div id='left-nav'>";

include ("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock ( $errors, $successes );

echo "
<form name='checkinLocaties' action='" . $_SERVER ['PHP_SELF'] . "' method='post'>
<table class='admin'>
<tr>
<th>Delete</th><th>Locatie</th>
</tr>";

// Cycle through users
foreach ( $userData as $v1 ) {
	echo "
	<tr>
	<td><input type='checkbox' name='delete[" . $v1 ['id'] . "]' id='delete[" . $v1 ['id'] . "]' value='" . $v1 ['id'] . "'></td>
	<td><a href='checkin_locatie.php?id=" . $v1 ['id'] . "'>" . $v1 ['locatie'] . "</a></td>
	</tr>";
}

echo "
</table>
<p>
<label>Locatie naam:</label>
<input type='text' name='newLocation' />
</p>                                
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
