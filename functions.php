<?php
	function check_id_login($con){
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

	//generates random number with a random length
	function random_num($length){
			$text = "";
			//making sure length of random number is never less than 5
			if($length < 5){
				$length = 5;
			}
			//generates random number between 4 and given value
			$len = rand(4, $length);

			for($i = 0; $i < $len; $i++){
				//adds a value between 0 and 9 on each iteration
				$text .= rand(0,9);
			}
			return $text;
	}

	function admin_list(){
		$db = new mysqli("localhost", "root", "", "inv_mgmt_db"); 
		//query for table containing order info as well as user's name id
		$query = "SELECT user_orders.*, users.user_id, users.user_name
				  FROM user_orders
				  JOIN users USING(user_id)";
	
		//creating table and table headers
		echo '<table id="orders">
			<tr>
				<th>User Name</td> 
				<th>User ID</td> 
				<th>Name</td> 
		        <th>Price</td> 
			        <th>Quantity</td>
		        <th>Date</td> 
		        <th>Type</td>
		        <th>Status</td> 
			        <th></td> 

		    </tr>';
		//running query against database
		$result = mysqli_query($db, $query);

		//iterating thru table and getting all needed data 
	    while ($row = mysqli_fetch_assoc($result)) {
	    	$user_name = $row['user_name'];
	    	$user_id = $row['user_id'];
	        $order_name = $row['order_name'];
	        $order_price = $row['order_price'];
	        $order_quantity = $row['order_quantity']; 
	        $order_date = $row['order_date'];
	        $order_type = $row['order_type']; 
	        $order_status = $row['order_status'];

	        //plotting data to table
	        echo '<tr> 
	        		<td>'.$user_name.'</td>
	        		<td>'.$user_id.'</td>
	                <td>'.$order_name.'</td> 
	                <td>'.$order_price.'</td> 
		            <td>'.$order_quantity.'</td> 
	                <td>'.$order_date.'</td> 
	                <td>'.$order_type.'</td> 
	                <td>'.$order_status.'</td>
	                <td>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit-modal">
			               	<img title="Edit order" alt="edit"
			               		src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/64/Edit_icon_%28the_Noun_Project_30184%29.svg/2048px-Edit_icon_%28the_Noun_Project_30184%29.svg.png">
		               	</button>
		               	&nbsp;&nbsp;
		               	<button type="button" class="btn btn-danger">
		               	<img title="Delete order" alt="delete"
		               		src="https://cdn-icons-png.flaticon.com/512/1345/1345874.png">
		               	</button>
	                </td>
	              </tr>';
	    }

	    //freeing memory
	    $result->free();
		
		//placeholder empty cell for when theres no other cells
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>&nbsp;</td></tr>';
	}
?>

<html>
<body>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>