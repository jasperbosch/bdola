<?php
//
function checkSession()
{
	if(!isset($_SESSION[SESSION_USER]))
	{
		$error = new error ();
		$error->type = "danger";
		$error->msg = "Authorisation Problem";
		return $error;
		
	}
	if (!isset($_SESSION[SESSION_USER]->username)){
		$error = new error ();
		$error->type = "danger";
		$error->msg = "Authorisation Problem";
		return $error;
	}
	return "OK";
}
