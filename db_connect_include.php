<?php

/* (Reference #16) */


//since the database connection is contained within a function call
//one must then only make the function call to open the database
function doDB() {
	global $mysqli;

	//this performs the database connection string
	//these are the database credentials on my laptop's local xampp installation
	$mysqli = mysqli_connect("localhost", "root", "PASSWORD_GOES_HERE", "se_700");

	//if connection fails, stop script execution
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
}
?>