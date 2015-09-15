<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$teamId = $_GET['id'];

//Check if selected permission level exists
if(!teamIdExists($teamId)){
	header("Location: checkin_teams.php"); die();	
}

$teamDetails = fetchTeamDetails($teamId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
	
	//Delete selected permission level
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteTeams($deletions)){
		$successes[] = lang("TEAM_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
		if(!teamIdExists($teamId)){
			header("Location: checkin_teams.php"); die();
		}
	}
	else
	{
		//Update permission level name
		$imckcChecked = false;
		$imckcChecked = ($teamDetails['imckc'] == 0 && $_POST['imckc']) || ($teamDetails['imckc'] == 1 && !isset($_POST['imckc']));
		if($teamDetails['naam'] != $_POST['naam'] || $teamDetails['rgb'] != $_POST['rgb'] || $imckcChecked) {
			$naam = trim($_POST['naam']);
			$rgb = trim($_POST['rgb']);
			$imckc = 0;
			if (isset($_POST['imckc'])){
				$imckc = $_POST['imckc'];
			}
			
			//Validate new name
			if (teamNameExists($naam) && $teamDetails['rgb'] == $_POST['rgb'] && !$imckcChecked){
				$errors[] = lang("TEAM_NAME_IN_USE", array($naam));
			}
			elseif (minMaxRange(1, 50, $naam)){
				$errors[] = lang("TEAM_CHAR_LIMIT", array(1, 50));	
			}
			else {
				if (updateTeam($teamId, $naam, $rgb, $imckc)){
					$successes[] = lang("TEAM_NAME_UPDATE", array($naam));
				}
				else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		

	}
}
$teamDetails = fetchTeamDetails ( $teamId );

require_once("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Admin Teams</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock($errors,$successes);

echo "
<form name='adminPermission' action='".$_SERVER['PHP_SELF']."?id=".$teamId."' method='post'>
<table class='admin'>
<tr><td>
<h3>Team Informatie</h3>
<div id='regbox'>
<p>
<label>ID:</label>
".$teamDetails['id']."
</p>
<p>
<label>Naam:</label>
<input type='text' name='naam' value='".$teamDetails['naam']."' />
</p>
<p>
<label>Kleur:</label>
<input type='text' name='rgb' value='".$teamDetails['rgb']."' />
</p>
<p>
<label>ImCkc:</label>
<input type='checkbox' name='imckc' value='1' ";
if ($teamDetails['imckc']==1){
	echo "checked";
}
echo " />
</p>
	<label>Delete:</label>
<input type='checkbox' name='delete[".$teamDetails['id']."]' id='delete[".$teamDetails['id']."]' value='".$teamDetails['id']."'>
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
