<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$configId = $_GET['id'];

//Check if selected permission level exists
if(!configIdExists($configId)){
	header("Location: checkin_configs.php"); die();	
}

$configDetails = fetchConfigDetails($configId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
	
	//Delete selected permission level
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteConfigs($deletions)){
		$successes[] = lang("CONFIG_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
		if(!configIdExists($configId)){
			header("Location: checkin_configs.php"); die();
		}
	}
	else
	{
		//Update permission level name
		if($configDetails['sleutel'] != $_POST['sleutel'] || $configDetails['waarde'] != $_POST['waarde']) {
			$sleutel = trim($_POST['sleutel']);
			$waarde = trim($_POST['waarde']);
			
			//Validate new name
			if (configNameExists($sleutel) && $configDetails['waarde'] == $_POST['waarde']){
				$errors[] = lang("CONFIG_NAME_IN_USE", array($sleutel));
			}
			elseif (minMaxRange(1, 50, $sleutel)){
				$errors[] = lang("CONFIG_CHAR_LIMIT", array(1, 50));	
			}
			else {
				if (updateChConfig($configId, $sleutel, $waarde)){
					$successes[] = lang("CONFIG_NAME_UPDATE", array($sleutel));
				}
				else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		

	}
}
$configDetails = fetchConfigDetails ( $configId );

require_once("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Admin Configs</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock($errors,$successes);

echo "
<form name='adminPermission' action='".$_SERVER['PHP_SELF']."?id=".$configId."' method='post'>
<table class='admin'>
<tr><td>
<h3>Config Informatie</h3>
<div id='regbox'>
<p>
<label>ID:</label>
".$configDetails['id']."
</p>
<p>
<label>Config:</label>
<input type='text' name='sleutel' value='".$configDetails['sleutel']."' />
</p>
<p>
<label>Waarde:</label>
<input type='text' name='waarde' value='".$configDetails['waarde']."' />
</p>
		<label>Delete:</label>
<input type='checkbox' name='delete[".$configDetails['id']."]' id='delete[".$configDetails['id']."]' value='".$configDetails['id']."'>
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
