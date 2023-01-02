<!DOCTYPE html>
<html>
<head>
	<script>"https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"</script>
	<link rel="stylesheet" href="../styles/access_page_styles.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign up</title>
</head>
<body>
	<div id="outer-box-1">
		<div id="outer-box-2">
			<div id="inner-box">
				<form method="post">
					<div id="header">Create a new account</div>
					<p>Username:</p>
					<input id="text" type="text" name="user_name" placeholder="Enter username"><br><br>
					<p>Password:</p>
					<input id="text" type="password" name="password" placeholder="Enter password"><br><br><br>
					<input id="button" type="submit" value="Sign up"><br><br>
					<?php
						session_start();
						include("connection.php");
						include("functions.php");

						//checking if user clicked signup button
						if($_SERVER['REQUEST_METHOD'] == "POST"){
							$user_name = $_POST['user_name'];
							$password = $_POST['password'];

							//making sure fields arent empty
							if(!empty($user_name) && !empty($password)){
								//selecting all usersnames
								$select = mysqli_query($con, "SELECT * FROM users 
									WHERE user_name = '".$_POST['user_name']."'");
								
								//checking if username already exists
								if(mysqli_num_rows($select)) {
								    echo "Error: username already exists";
								}
								
								//checking if username is alphanumeric
								else if(is_numeric($user_name)){
									echo "Error: username cannot only contain numbers";
								}

								//creating new account
								else{
									//generating random id number
									$user_id = rand(0,9999);
									//saving registration info to db
									$query = "INSERT INTO users(user_id, user_name, password) 
										      VALUES('$user_id', '$user_name', '$password')";
									mysqli_query($con, $query);

									//redirection to login page
									echo "Account successfully registered!";
									header("Location: login.php");
									die;
								}
							}	

							//username and/or password fields left empty
							else{
								echo "Error: username and/or password fields cannot be empty";
							}
						}
					?>
					<br>
					<a href="login.php">Back to login page</a>
				</form>
			</div>
		</div>
	</div>
</body>
</html>