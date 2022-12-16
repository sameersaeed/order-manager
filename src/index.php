<?php
    session_start();
    include("../include/connection.php");
    include("../include/functions.php");

    //gets data of user and order using id
    $user_data = check_id_user($con);
    $order_data = check_id_order($con);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../styles/index_styles.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $user_data['user_name']; ?>'s home page</title>
</head>
<body>
    <div class="nav">
        <a href="../include/logout.php">Logout</a>
    </div>
    <br>
    <p1>Hello, <?php echo $user_data['user_name']; ?>.</p1>
    <br><br><br><br><br>
    <form method="post">
        <div class="box-body">
            <p>Create new transaction</p>
            <div class="form-group row" >
                <input name="order_id" type="hidden" id="id" value="1">
                    <label class="text-right col-sm-4 col-form-label">Enter Name</label>
                        <div class="col-sm-8"> 
                            <input name="order_name" type="text" id="name"  class="form-control">
                        </div>
            </div>
            <div class="form-group row">
                <label class="text-right col-sm-4 col-form-label">Enter Price</label>
                <div class="col-sm-8">
                    <input name="order_price" type="text" id="price" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="text-right col-sm-4 col-form-label">Enter Quantity</label>
                <div class="col-sm-8"> 
                    <input name="order_quantity" type="text" id="quantity" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="text-right col-sm-4 col-form-label">Enter Date</label>
                <div class="col-sm-8"> 
                    <input name="order_date" type="date" id="date" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <label class="text-right col-sm-4 col-form-label">Enter Type</label>
                <div class="col-sm-8">
                    <select class="form-control select1" name="order_type" id="type" data-width="100%">
                        <option>Select type</option>
                        <option value="Buying">Buying</option>
                        <option value="Selling">Selling</option>
                        <option value="Renting">Renting</option>
                        <option value="Loaning">Loaning</option>
                        <option value="Other">Other</option>

                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="text-right col-sm-4 col-form-label">Enter Status</label>
                <div class="col-sm-8">
                    <select class="form-control select2" name="order_status" id="status" data-width="100%">
                        <option>Select status</option>
                        <option value="In progress">In progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <b style="color: white;">* - required</b>
            <button type="submit" name="update" class="btn btn-primary  pull-right">Create new order</button>
        </div>
    </div>
    <br>
    <?php           
        //posting order data to db 
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $user_id = $user_data['user_id'];
            $order_id = random_num(20);
            $order_name = $_POST['order_name'];
            $order_price = $_POST['order_price'];
            $order_quantity = $_POST['order_quantity'];
            $order_date = $_POST['order_date'];
            $order_type = $_POST['order_type'];
            $order_status = $_POST['order_status'];

            if(!empty($order_name) && !empty($order_status)){
                //inserts order into db
                $query = "INSERT INTO user_orders(user_id, order_id, order_name, order_price, 
                    order_quantity, order_date, order_type, order_status) 
                VALUES('$user_id', '$order_id', '$order_name', '$order_price',  '$order_quantity', 
                    '$order_date', '$order_type', '$order_status')";
                mysqli_query($con, $query);
                //exits to prevent unintended inserts
                header("Location: index.php");
                exit;
            }
        }
    ?>
    </form>
    <div id="viewedit">
        <br><br><br><br><br>
        View and edit transactions
        <?php
            //connecting to db and selecting everything
            $dbhost = "localhost";
            $dbuser = "root";
            $dbpass = "";
            $dbname = "inv_mgmt_db";
            $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname); 

            $user_id = $user_data['user_id'];
            
            //checks for admin user, admins have id=0 and can see all users' orders
            if($user_id == 0){
                $query = "SELECT user_orders.*, users.user_id, users.user_name
                            FROM user_orders
                          JOIN users USING(user_id)";            
            }
            //not an admin user, query changed to not show all orders
            else{
                $query = "SELECT * FROM user_orders WHERE user_id IN (SELECT user_id FROM users WHERE user_id ='$user_id');";
            }

            //admin can view two more cols, for users name and id
            if($user_id == 0){ 
                echo '<div class="table-responsive">    
                        <table id="adminorders">
                            <thead>
                                <tr>
                                    <th>User Name</td> 
                                    <th>User ID</td>';
            }
            else{ //user table
                echo '<div class="table-responsive">    
                        <table id="orders">
                            <thead>
                                <tr>';
            }

            //creating table and table headers
            echo '  <th>Order ID</td> 
                    <th>Name</td> 
                    <th>Price</td> 
                    <th>Quantity</td>
                    <th>Date</td> 
                    <th>Type</td>
                    <th>Status</td> 
                  </tr>
                </thead>';

            //iterating thru table and getting all needed data 
            $result = mysqli_query($db, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                //plotting data to table
                if($user_id == 0){ //plotting user name+id rows for admin view
                    echo '<tr>
                            <td>'.$row['user_name'].'</td>
                            <td>'.$row['user_id'].'</td>';
                }
                echo '<td>'.$row['order_id'].'</td> 
                      <td>'.$row['order_name'].'</td> 
                      <td>'.$row['order_price'].'</td> 
                      <td>'.$row['order_quantity'].'</td> 
                      <td>'.$row['order_date'].'</td> 
                      <td>'.$row['order_type'].'</td>
                      <td>'.$row['order_status'].'</td> 
                    </tr>';
            }
            //freeing memory
            $result->free();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery-tabledit@1.0.0/jquery.tabledit.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <?php
        if($user_data['user_id'] == 0){ //admin table script
            echo "<script src=\"..\"scripts\"sadmin_table.js\"></script>";
        }
        else{ //user table script
            echo "<script src=\"..\"scripts\"table.js\"></script>";
        }
    ?>
</body>
</html>