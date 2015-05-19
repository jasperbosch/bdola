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
		if ($deletion_count = deleteConfigs ( $deletions )) {
			$successes [] = lang ( "CONFIG_DELETIONS_SUCCESSFUL", array (
					$deletion_count 
			) );
		} else {
			$errors [] = lang ( "SQL_ERROR" );
		}
	}
	
	// Create new permission level
	if (! empty ( $_POST ['newConfig'] )) {
		$config = trim ( $_POST ['newConfig'] );
		
		// Validate request
		if (configNameExists ( $config )) {
			$errors [] = lang ( "CONFIG_NAME_IN_USE", array (
					$config 
			) );
		} elseif (minMaxRange ( 1, 50, $config )) {
			$errors [] = lang ( "CONFIG_CHAR_LIMIT", array (
					1,
					50 
			) );
		} else {
			if (createConfig ( $config )) {
				$successes [] = lang ( "CONFIG_CREATION_SUCCESSFUL", array (
						$config 
				) );
			} else {
				$errors [] = lang ( "SQL_ERROR" );
			}
		}
	}
}

$userData = fetchAllConfigs (); // Fetch information for all configs

require_once ("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Checkin Configs</h2>
<div id='left-nav'>";

include ("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock ( $errors, $successes );

echo "
<form name='checkinConfigs' action='" . $_SERVER ['PHP_SELF'] . "' method='post'>
<table class='admin'>
<tr>
<th>Delete</th><th>Config</th><th>Waarde</th>
</tr>";

// Cycle through users
foreach ( $userData as $v1 ) {
	echo "
	<tr>
	<td><input type='checkbox' name='delete[" . $v1 ['id'] . "]' id='delete[" . $v1 ['id'] . "]' value='" . $v1 ['id'] . "'></td>
	<td><a href='checkin_config.php?id=" . $v1 ['id'] . "'>" . $v1 ['sleutel'] . "</a></td>
	<td>".$v1['waarde']."</td>
	</tr>";
}

echo "
</table>
<p>
<label>Config key:</label>
<input type='text' name='newConfig' />
</p>                                
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
