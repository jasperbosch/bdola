<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$locatieId = $_GET['id'];

//Check if selected permission level exists
if(!locationIdExists($locatieId)){
	header("Location: checkin_locaties.php"); die();	
}

$locationDetails = fetchLocationDetails($locatieId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
	
	//Delete selected permission level
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteLocations($deletions)){
		$successes[] = lang("LOCATION_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
		if(!locationIdExists($locatieId)){
			header("Location: checkin_locaties.php"); die();
		}
	}
	else
	{
		//Update permission level name
		if($locationDetails['location'] != $_POST['location']) {
			$location = trim($_POST['location']);
			
			//Validate new name
			if (locationNameExists($location)){
				$errors[] = lang("LOCATION_NAME_IN_USE", array($location));
			}
			elseif (minMaxRange(1, 50, $location)){
				$errors[] = lang("LOCATION_CHAR_LIMIT", array(1, 50));	
			}
			else {
				if (updateLocationName($locatieId, $location)){
					$successes[] = lang("LOCATION_NAME_UPDATE", array($location));
				}
				else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		

	}
}
$locationDetails = fetchLocationDetails ( $locatieId );

require_once("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Admin Locaties</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock($errors,$successes);

echo "
<form name='adminPermission' action='".$_SERVER['PHP_SELF']."?id=".$locatieId."' method='post'>
<table class='admin'>
<tr><td>
<h3>Locatie Informatie</h3>
<div id='regbox'>
<p>
<label>ID:</label>
".$locationDetails['id']."
</p>
<p>
<label>Locatie:</label>
<input type='text' name='location' value='".$locationDetails['location']."' />
</p>
<label>Delete:</label>
<input type='checkbox' name='delete[".$locationDetails['id']."]' id='delete[".$locationDetails['id']."]' value='".$locationDetails['id']."'>
</p>
</div></td>
</tr>
</table>
<p>
<label>&nbsp;</label>
<input type='submit' value='Update' class='submit' />
</p>
</form>
</div>
<div id='bottom'></div>
</div>
</body>
</html>";

?>
