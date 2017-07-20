<?php

	require("constant.php");

	$connection=mysqli_connect(DB_SERVER,DB_USER,DB_PASS);
	if(!$connection){
		die("Database connection failed : ".mysqli_error($connection));
	}

	$db_sel=mysqli_select_db($connection,DB_NAME);
	if(!$db_sel){
		die("Database connection failed : ".mysqli_error($connection));
	}
	
?>