<?php
	session_start();
	//if user is logged in, logs out and redirects to login page
	if(isset($_SESSION['user_id'])){
		unset($_SESSION['user_id']);
	}

	header("Location: login.php");
	die;
?>