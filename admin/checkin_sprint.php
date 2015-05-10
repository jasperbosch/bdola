<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$sprintId = $_GET['id'];

//Check if selected permission level exists
if(!sprintIdExists($sprintId)){
	header("Location: checkin_sprintstarts.php"); die();	
}

$sprintDetails = fetchSprintDetails($sprintId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
	
	//Delete selected permission level
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteSprints($deletions)){
		$successes[] = lang("SPRINT_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
		if(!sprintIdExists($sprintId)){
			header("Location: checkin_sprintstarts.php"); die();
		}
	}
	else
	{
		//Update permission level name
		if($sprintDetails['naam'] != $_POST['naam'] || $sprintDetails['datum'] != $_POST['datum']) {
			$naam = trim($_POST['naam']);
			$datum = trim($_POST['datum']);
			
			//Validate new name
			if (sprintNameExists($naam) && $sprintDetails['datum'] == $_POST['datum']){
				$errors[] = lang("SPRINT_NAME_IN_USE", array($naam));
			}
			elseif (minMaxRange(1, 50, $naam)){
				$errors[] = lang("SPRINT_CHAR_LIMIT", array(1, 50));	
			}
			else {
				if (updateSprint($sprintId, $naam, $datum)){
					$successes[] = lang("SPRINT_NAME_UPDATE", array($naam));
				}
				else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		

	}
}
$sprintDetails = fetchSprintDetails ( $sprintId );

require_once("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Admin Sprints</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock($errors,$successes);

echo "
<form name='adminPermission' action='".$_SERVER['PHP_SELF']."?id=".$sprintId."' method='post'>
<table class='admin'>
<tr><td>
<h3>Sprint Informatie</h3>
<div id='regbox'>
<p>
<label>ID:</label>
".$sprintDetails['id']."
</p>
<p>
<label>Naam:</label>
<input type='text' name='naam' value='".$sprintDetails['naam']."' />
</p>
<p>
<label>Sprintstart:</label>
<input type='text' name='datum' value='".$sprintDetails['datum']."' placeholder='jjjj-dd-mm'/> (jjjj-mm-dd)
</p>
		<label>Delete:</label>
<input type='checkbox' name='delete[".$sprintDetails['id']."]' id='delete[".$sprintDetails['id']."]' value='".$sprintDetails['id']."'>
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
