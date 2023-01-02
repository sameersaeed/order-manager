<?php
	function check_id_user($con){
		//check if session exists
		if(isset($_SESSION['user_id'])){ 
			//get user id
			$id = $_SESSION['user_id'];
			$query = "SELECT * FROM users WHERE user_id = '$id' LIMIT 1";
			$result = mysqli_query($con, $query);
			
			//checking if user exists in db
			if($result && mysqli_num_rows($result) > 0){ 
				$user_data = mysqli_fetch_assoc($result);
				return $user_data;
			}
		}

		//if login unsuccessful, stays on login page
		header("Location: login.php");
		die;
	}

	function check_id_order($con){
		if(isset($_SESSION['user_id'])){ 
			$id = $_SESSION['user_id'];
			$query = "SELECT * FROM user_orders WHERE user_id = '$id'";
			$result = mysqli_query($con, $query);
			
			if($result && mysqli_num_rows($result) > 0){ 
				$user_data = mysqli_fetch_assoc($result);
				return $user_data;
			}
		}
	}
?>
<html>
<body>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>