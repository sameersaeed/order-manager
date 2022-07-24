<?php
    session_start();
    include("connection.php");
    include("functions.php");

    $user_data = check_id_login($con);
    $order_data = check_id_order($con);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="index_styles.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $user_data['user_name']; ?>'s home page</title>
</head>
<body>
    <a href="logout.php">Logout</a>
    <h1>This is the index page</h1>
    <br>
    <p1>Hello, <?php echo $user_data['user_name']; ?>.</p1>
    <br><br><br><br><br>
    <div id="create">
        <form method="post">
            <p>Create a new transaction</p>
            <br><br>
            <p>Name*</p>  
            <input id="text" type="text" name="order_name" placeholder="Enter name"><br><br>
            
            <p>Price</p>   
            <input id="text" type="text" name="order_price" placeholder="Enter price ($CAD)"><br><br>
            
            <p>Quantity</p>   
            <input id="text" type="text" name="order_quantity" placeholder="Enter quantity"><br><br>

            <p>Date</p>   
            <input id="text" type="date" name="order_date" placeholder="Enter date"><br><br>
            
            <label style="font-weight: normal;" for="order_status">
            <p>Status*</p>
            </label>
            <select id="type" name="order_status">
                <option>Select status</option>
                <option value="In progress">In progress</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select><br><br>
            
            <label style="font-weight: normal;" for="order_type">
            <p>Type</p>
            </label>
            <select id="type" name="order_type">
                <option>Select type</option>
                <option value="Buying">Buying</option>
                <option value="Selling">Selling</option>
                <option value="Renting">Renting</option>
                <option value="Loaning">Loaning</option>
                <option value="Other">Other</option>
            </select><br><br>
            <input id="button" type="submit" value="Create new order"><br><br>

            * - required
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
    </div>
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
    <?php
        echo "<script src=\"https://cdn.jsdelivr.net/npm/jquery-tabledit@1.0.0/jquery.tabledit.min.js\"></script>";
        echo "<script src=\"https://code.jquery.com/ui/1.13.2/jquery-ui.js\"></script>";

        if($user_data['user_id'] == 0){ //admin table script
            echo "<script>
                    $('#adminorders').Tabledit({
                        url: 'db_updater.php',
                        columns: {
                            identifier: [2, 'order_id'],
                            editable:[[3, 'order_name'], [4, 'order_price'], [5, 'order_quantity'], [6, 'order_date'], [7, 'order_type', '{\"Select type\": \"Select type\", \"Buying\": \"Buying\", \"Selling\": \"Selling\", \"Renting\": \"Renting\", \"Loaning\": \"Loaning\", \"Other\": \"Other\"}'], [8, 'order_status', '{\"Select status\": \"Select status\", \"In progress\": \"In progress\", \"Completed\": \"Completed\", \"Cancelled\": \"Cancelled\"}']]
                        },
                        onDraw: function() {
                            $('#adminorders td:nth-child(7) input').each(function() {
                                $(this).datepicker({
                                    dateFormat: 'yy-mm-dd',
                                    todayHighlight: true
                                });
                            });
                            console.log('onDraw()');
                        },
                        onSuccess: function(data, textStatus, jqXHR) {
                            if(data.action == 'delete'){
                                $('#' + data.id).remove();
                                $('#adminorders').DataTable().ajax.reload();
                            }
                            console.log('onSuccess(data, textStatus, jqXHR)');
                            console.log(data);
                            console.log(textStatus);
                            console.log(jqXHR);
                                location.reload();
                        },
                        onFail: function(jqXHR, textStatus, errorThrown) {
                            console.log('onFail(jqXHR, textStatus, errorThrown)');
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                                location.reload();
                        },
                        onAlways: function() {
                            console.log('onAlways()');
                        },
                        onAjax: function(action, serialize) {
                            console.log('onAjax(action, serialize)');
                            console.log(action);
                            console.log(serialize);
                        }
                    });
                </script>"
            ;
        }
        else{ //user table script
            echo "<script>  
                    $('#orders').Tabledit({
                        url: 'db_updater.php',
                        columns: {
                            identifier:[0, 'order_id'],
                            editable:[[1, 'order_name'], [2, 'order_price'], [3, 'order_quantity'], [4, 'order_date'], [5, 'order_type', '{\"Select type\": \"Select type\", \"Buying\": \"Buying\", \"Selling\": \"Selling\", \"Renting\": \"Renting\", \"Loaning\": \"Loaning\", \"Other\": \"Other\"}'], [6, 'order_status', '{\"Select status\": \"Select status\", \"In progress\": \"In progress\", \"Completed\": \"Completed\", \"Cancelled\": \"Cancelled\"}']]
                        },
                        onDraw: function() {
                            $('#orders td:nth-child(5) input').each(function() {
                                $(this).datepicker({
                                    dateFormat: 'yy-mm-dd',
                                    todayHighlight: true
                                });
                            });
                            console.log('onDraw()');
                        },
                        onSuccess: function(data, textStatus, jqXHR) {
                            if(data.action == 'delete'){
                                $('#' + data.id).remove();
                                $('#orders').DataTable().ajax.reload();
                            }
                            console.log('onSuccess(data, textStatus, jqXHR)');
                            console.log(data);
                            console.log(textStatus);
                            console.log(jqXHR);
                                location.reload();
                        },
                        onFail: function(jqXHR, textStatus, errorThrown) {
                            console.log('onFail(jqXHR, textStatus, errorThrown)');
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorThrown);
                                location.reload();
                        },
                        onAlways: function() {
                            console.log('onAlways()');
                        },
                        onAjax: function(action, serialize) {
                            console.log('onAjax(action, serialize)');
                            console.log(action);
                            console.log(serialize);
                        }
                    });
                </script>"
            ;
        }
    ?>
</body>
</html>