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
		if ($deletion_count = deleteTeams ( $deletions )) {
			$successes [] = lang ( "TEAM_DELETIONS_SUCCESSFUL", array (
					$deletion_count 
			) );
		} else {
			$errors [] = lang ( "SQL_ERROR" );
		}
	}
	
	// Create new permission level
	if (! empty ( $_POST ['newTeam'] )) {
		$team = trim ( $_POST ['newTeam'] );
		
		// Validate request
		if (teamNameExists ( $team )) {
			$errors [] = lang ( "TEAM_NAME_IN_USE", array (
					$team 
			) );
		} elseif (minMaxRange ( 1, 50, $team )) {
			$errors [] = lang ( "TEAM_CHAR_LIMIT", array (
					1,
					50 
			) );
		} else {
			if (createTeam ( $team )) {
				$successes [] = lang ( "TEAM_CREATION_SUCCESSFUL", array (
						$team 
				) );
			} else {
				$errors [] = lang ( "SQL_ERROR" );
			}
		}
	}
}

$userData = fetchAllTeams (); // Fetch information for all teams

require_once ("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Checkin Teams</h2>
<div id='left-nav'>";

include ("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock ( $errors, $successes );

echo "
<form name='checkinTeams' action='" . $_SERVER ['PHP_SELF'] . "' method='post'>
<table class='admin'>
<tr>
<th>Delete</th><th>Team</th><th>Kleur</th>
</tr>";

// Cycle through users
foreach ( $userData as $v1 ) {
	echo "
	<tr>
	<td><input type='checkbox' name='delete[" . $v1 ['id'] . "]' id='delete[" . $v1 ['id'] . "]' value='" . $v1 ['id'] . "'></td>
	<td><a href='checkin_team.php?id=" . $v1 ['id'] . "'>" . $v1 ['naam'] . "</a></td>
	<td style='background-color:".$v1['rgb'].";'>".$v1['rgb']."</td>
	</tr>";
}

echo "
</table>
<p>
<label>Team naam:</label>
<input type='text' name='newTeam' />
</p>                                
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
