<?php
	$connect = mysqli_connect('localhost', 'root', '', 'inv_mgmt_db');
	$input = filter_input_array(INPUT_POST);


	$order_name = mysqli_real_escape_string($connect, $input["order_name"]);
	$order_price = mysqli_real_escape_string($connect, $input["order_price"]);
	$order_quantity = mysqli_real_escape_string($connect, $input["order_quantity"]);
	$order_date = mysqli_real_escape_string($connect, $input["order_date"]);
	$order_type = mysqli_real_escape_string($connect, $input["order_type"]);
	$order_status = mysqli_real_escape_string($connect, $input["order_status"]);

	echo $order_type;

	if($input["action"] === 'edit'){
	    $query = "UPDATE user_orders 
	    			SET order_name = '".$order_name."', 
	    			     order_price = '".$order_price."', 
   	    			     order_quantity = '".$order_quantity."', 
	    			     order_date = '".$order_date."', 
	    			     order_type = '".$order_type."', 
	    			     order_status = '".$order_status."' 
	    			WHERE order_id = '".$input["order_id"]."'";
	    mysqli_query($connect, $query);    
	}

	if($input["action"] === 'delete'){
	    $query = "DELETE FROM user_orders 
	    WHERE order_id = '".$input["order_id"]."'";
	    mysqli_query($connect, $query);   
	}
	header('Location: ../src/index.php');
	echo json_encode($input);
?>
