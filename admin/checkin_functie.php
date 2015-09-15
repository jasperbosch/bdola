<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
$functieId = $_GET['id'];

//Check if selected permission level exists
if(!functieIdExists($functieId)){
	header("Location: checkin_functies.php"); die();	
}

$functieDetails = fetchFunctieDetails($functieId); //Fetch information specific to permission level

//Forms posted
if(!empty($_POST)){
	
	//Delete selected permission level
	if(!empty($_POST['delete'])){
		$deletions = $_POST['delete'];
		if ($deletion_count = deleteFuncties($deletions)){
		$successes[] = lang("FUNCTIE_DELETIONS_SUCCESSFUL", array($deletion_count));
		}
		else {
			$errors[] = lang("SQL_ERROR");	
		}
		if(!functieIdExists($functieId)){
			header("Location: checkin_functies.php"); die();
		}
	}
	else
	{
		//Update permission level name
		if($functieDetails['naam'] != $_POST['naam'] || $functieDetails['code'] != $_POST['code']) {
			$naam = trim($_POST['naam']);
			$code = trim($_POST['code']);
			
			//Validate new name
			if (functieNameExists($naam) && $functieDetails['code'] == $_POST['code']){
				$errors[] = lang("FUNCTIE_NAME_IN_USE", array($naam));
			}
			elseif (minMaxRange(1, 50, $naam)){
				$errors[] = lang("FUNCTIE_CHAR_LIMIT", array(1, 50));	
			}
			else {
				if (updateFunctie($functieId, $naam, $code)){
					$successes[] = lang("FUNCTIE_NAME_UPDATE", array($naam));
				}
				else {
					$errors[] = lang("SQL_ERROR");
				}
			}
		}
		

	}
}
$functieDetails = fetchFunctieDetails ( $functieId );

require_once("models/header.php");
echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<h1>UserCake</h1>
<h2>Admin Functies</h2>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<div id='main'>";

echo resultBlock($errors,$successes);

echo "
<form name='adminPermission' action='".$_SERVER['PHP_SELF']."?id=".$functieId."' method='post'>
<table class='admin'>
<tr><td>
<h3>Functie Informatie</h3>
<div id='regbox'>
<p>
<label>ID:</label>
".$functieDetails['id']."
</p>
<p>
<label>Naam:</label>
<input type='text' name='naam' value='".$functieDetails['naam']."' />
</p>
<p>
<label>Code:</label>
<input type='text' name='code' value='".$functieDetails['code']."' />
</p>
		<label>Delete:</label>
<input type='checkbox' name='delete[".$functieDetails['id']."]' id='delete[".$functieDetails['id']."]' value='".$functieDetails['id']."'>
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
