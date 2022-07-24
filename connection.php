<?php
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "inv_mgmt_db";
	
	//show error if cant connect
	if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){
		die("Failed to connect");
	}
?>