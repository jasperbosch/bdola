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
		if ($deletion_count = deleteFuncties ( $deletions )) {
			$successes [] = lang ( "FUNCTIE_DELETIONS_SUCCESSFUL", array (
					$deletion_count 
			) );
		} else {
			$errors [] = lang ( "SQL_ERROR" );
		}
	}
	
	// Create new permission level
	if (! empty ( $_POST ['newFunctie'] )) {
		$functie = trim ( $_POST ['newFunctie'] );
		
		// Validate request
		if (functieNameExists ( $functie )) {
			$errors [] = lang ( "FUNCTIE_NAME_IN_USE", array (
					$functie 
			) );
		} elseif (minMaxRange ( 1, 50, $functie )) {
			$errors [] = lang ( "FUNCTIE_CHAR_LIMIT", array (
					1,
					50 
			) );
		} else {
			if (createFunctie ( $functie )) {
				$successes [] = lang ( "FUNCTIE_CREATION_SUCCESSFUL", array (
						$functie 
				) );
			} else {
				$errors [] = lang ( "SQL_ERROR" );
			}
		}
	}
}

$userData = fetchAllFuncties (); // Fetch information for all functies

require_once ("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Checkin Functies</h2>
<div id='left-nav'>";

include ("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock ( $errors, $successes );

echo "
<form name='checkinFuncties' action='" . $_SERVER ['PHP_SELF'] . "' method='post'>
<table class='admin'>
<tr>
<th>Delete</th><th>Functie</th><th>Code</th>
</tr>";

// Cycle through users
foreach ( $userData as $v1 ) {
	echo "
	<tr>
	<td><input type='checkbox' name='delete[" . $v1 ['id'] . "]' id='delete[" . $v1 ['id'] . "]' value='" . $v1 ['id'] . "'></td>
	<td><a href='checkin_functie.php?id=" . $v1 ['id'] . "'>" . $v1 ['naam'] . "</a></td>
	<td>".$v1['code']."</td>
	</tr>";
}

echo "
</table>
<p>
<label>Functie naam:</label>
<input type='text' name='newFunctie' />
</p>                                
<input type='submit' name='Submit' value='Submit' />
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
