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
		if ($deletion_count = deleteSprints ( $deletions )) {
			$successes [] = lang ( "SPRINT_DELETIONS_SUCCESSFUL", array (
					$deletion_count 
			) );
		} else {
			$errors [] = lang ( "SQL_ERROR" );
		}
	}
	
	// Create new permission level
	if (! empty ( $_POST ['newSprint'] )) {
		$sprint = trim ( $_POST ['newSprint'] );
		
		// Validate request
		if (sprintNameExists ( $sprint )) {
			$errors [] = lang ( "SPRINT_NAME_IN_USE", array (
					$sprint 
			) );
		} elseif (minMaxRange ( 1, 50, $sprint )) {
			$errors [] = lang ( "SPRINT_CHAR_LIMIT", array (
					1,
					50 
			) );
		} else {
			if (createSprint ( $sprint )) {
				$successes [] = lang ( "SPRINT_CREATION_SUCCESSFUL", array (
						$sprint 
				) );
			} else {
				$errors [] = lang ( "SQL_ERROR" );
			}
		}
	}
}

$userData = fetchAllSprints (); // Fetch information for all sprints

require_once ("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Checkin Sprints</h2>
<div id='left-nav'>";

include ("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock ( $errors, $successes );

echo "
<form name='checkinSprints' action='" . $_SERVER ['PHP_SELF'] . "' method='post'>
<table class='admin'>
<tr>
<th>Delete</th><th>Sprint</th><th>Startdatum</th>
</tr>";

// Cycle through users
foreach ( $userData as $v1 ) {
	echo "
	<tr>
	<td><input type='checkbox' name='delete[" . $v1 ['id'] . "]' id='delete[" . $v1 ['id'] . "]' value='" . $v1 ['id'] . "'></td>
	<td><a href='checkin_sprint.php?id=" . $v1 ['id'] . "'>" . $v1 ['naam'] . "</a></td>
	<td>".$v1['datum']."</td>
	</tr>";
}

echo "
</table>
<p>
<label>Sprint naam:</label>
<input type='text' name='newSprint' />
</p>                                
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
