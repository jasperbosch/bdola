<?php
session_start ();
if (isset ( $_SESSION ["userCakeUser"] )) {
	$data = array (
			'id' => session_id (),
			'user' => $_SESSION ["userCakeUser"] 
	);
} else {
	$data = array (
			'id' => session_id ()
	);
	
}
echo json_encode ( $data );