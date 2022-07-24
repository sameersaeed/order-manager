<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Welcome - log in or sign up</title>
</head>
<body>
	<style type="text/css">
		#text{
			font-family: monospace;
			height: 25px;
			border-radius: 5px;
			border: solid thin #aaa;
			width: 100%;
			padding: 5px;
		}
		#button{
			border-radius: 15px;
			width: 50%; 
			margin-left: 25%;	
			padding: 10px;
			color: white;
			background-color: dimgray;
			border: none;
		}
		#inner-box{
			width: 10vw;
		    margin: auto;
			padding: 20px;
		    position: relative;
			background-color: whitesmoke;
		}
		#header{
			font-family: monospace;
			padding-bottom: 15px;
			font-size: 20px; 
			text-align: center; 
			color: black;
			font-weight: bold;
		}
		p{
			font-family: sans-serif;
			font-size:105%
		}
	</style>
	<div id="inner-box">
		<form method="post">
			<div id="header">Login</div>
	
			<p>Username:</p>
			<input id="text" type="text" name="user_name" placeholder="Enter username"><br><br>
			<p>Password:</p>
			<input id="text" type="password" name="password" placeholder="Enter password"><br><br><br>
			<input id="button" type="submit" value="Log in"><br><br>
			<?php
				session_start();
				include("connection.php");
				include("functions.php");

				if($_SERVER['REQUEST_METHOD'] == "POST"){
					$user_name = $_POST['user_name'];
					$password = $_POST['password'];

					if(!empty($user_name) && !empty($password)){
						//selecting username and performing query
						$query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";
						$result = mysqli_query($con, $query);

						//checking if user exists in db
						if($result && mysqli_num_rows($result) > 0){
							//fetches their info
							$user_data = mysqli_fetch_assoc($result);

							//if password is correct
							if($user_data['password'] === $password){
								//login successful, redirect to user's home page
								$_SESSION['user_id'] = $user_data['user_id'];
								header("Location: index.php");
								die;
							}

							//if user exists but password is entered incorrectly
							else if($user_data['user_name'] === $user_name && 
								$user_data['password'] != $password){
								echo "Error: invalid password";
							}
						}

						//making sure username is not just numeric
						else if(is_numeric($user_name)){
							echo "Error: account does not exist; usernames cannot only contain numbers";
						}

						else{
							echo "Error: user does not exist";
						}
					}

					//username and/or password fields left empty
					else{
						echo "Error: username and/or password fields cannot be empty";
					}
				}
			?>
			<br>
			<a href="signup.php">Create a new account</a>
		</form>
	</div>
</body>
</html>