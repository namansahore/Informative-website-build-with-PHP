<?php require_once("includes/functions.php"); ?>
<?php

	// FIND SESSION
	session_start();

	// EMPTY SESSION
	$_SESSION = array();

	// DISTROY COOKIE
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(),'',time()-4200,'/');
	}

	// DISTROY SESSION
	session_destroy();

	redirect_to("login.php?logout=1"); 

?>