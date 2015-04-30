<?php
/*
 * UserCake Version: 2.0.2
 * http://usercake.com
 */
require_once ("models/config.php");
require_once ("checkstatus.php");
if (! securePage ( $_SERVER ['PHP_SELF'] )) {
	die ();
}

$postdata = file_get_contents ( "php://input" );
$request = json_decode ( $postdata );

$_POST ["username"] = $request->username;
$_POST ["password"] = $request->password;

// Forms posted
if (! empty ( $_POST )) {
	$errors = array ();
	$username = sanitize ( trim ( $_POST ["username"] ) );
	$password = trim ( $_POST ["password"] );
	
	// Perform some validation
	// Feel free to edit / change as required
	if ($username == "") {
		$error = new error ();
		$error->type = "danger";
		$error->msg = lang ( "ACCOUNT_SPECIFY_USERNAME" );
		$errors [] = $error;
	}
	if ($password == "") {
		$error = new error ();
		$error->type = "danger";
		$error->msg = lang ( "ACCOUNT_SPECIFY_PASSWORD" );
		$errors [] = $error;
	}
	
	if (count ( $errors ) == 0) {
		// A security note here, never tell the user which credential was incorrect
		if (! usernameExists ( $username )) {
			$error = new error ();
			$error->type = "danger";
			$error->msg = lang ( "ACCOUNT_USER_OR_PASS_INVALID" );
			$errors [] = $error;
		} else {
			$userdetails = fetchUserDetails ( $username );
			// See if the user's account is activated
			if ($userdetails ["active"] == 0) {
				$error = new error ();
				$error->type = "danger";
				$error->msg = lang ( "ACCOUNT_INACTIVE" );
				$errors [] = $error;
			} else {
				// Hash the password and use the salt from the database to compare the password.
				$entered_pass = generateHash ( $password, $userdetails ["password"] );
				
				if ($entered_pass != $userdetails ["password"]) {
					// Again, we know the password is at fault here, but lets not give away the combination incase of someone bruteforcing
					$error = new error ();
					$error->type = "danger";
					$error->msg = lang ( "ACCOUNT_USER_OR_PASS_INVALID" );
					$errors [] = $error;
				} else {
					// Passwords match! we're good to go'
					
					// Construct a new logged in user object
					// Transfer some db data to the session object
					$loggedInUser = new loggedInUser ();
					$loggedInUser->email = $userdetails ["email"];
					$loggedInUser->user_id = $userdetails ["id"];
					$loggedInUser->hash_pw = $userdetails ["password"];
					$loggedInUser->title = $userdetails ["title"];
					$loggedInUser->displayname = $userdetails ["display_name"];
					$loggedInUser->username = $userdetails ["user_name"];
					
					$locatie = -1;//getCheckinStatus($userdetails ["user_name"],$db);
					$loggedInUser->checkstatus = ($locatie != -1);
					if ($locatie != -1){
						$loggedInUser->locatie = $locatie;
					}
					
					// Update last sign in
					$loggedInUser->updateLastSignIn ();
					$_SESSION ["userCakeUser"] = $loggedInUser;
					$data = array (
							'id' => session_id (),
							'user' => $loggedInUser 
					);
				}
			}
		}
	}
}
if (count ( $errors ) > 0) {
	$data = array (
			'id' => session_id (),
			'error' => $errors 
	);
}
echo json_encode ( $data );

?>
