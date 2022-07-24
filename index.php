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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $user_data['user_name']; ?>'s home page</title>
</head>
<style type="text/css">
#orders{
    font-family: sans-serif;
    border-collapse: collapse;
    width: 95%;
}
#orders tr:nth-child(even){
    background-color: #e3effa;
}
#orders tr:hover, #order th:not(:first-child) hover{
    background-color: #c9c9c9;
}
#orders th{
    color: white;
    background-color: #0087ff;
    padding-top: 15px;
    padding-bottom: 15px;
}
#orders th, #orders td{
    text-align: center;
    border-bottom: 2px solid black;
    padding: 2px;
}
</style>

<body>
    <a href="logout.php">Logout</a>
    <h1>This is the index page</h1>
    <br>
    Hello, <?php echo $user_data['user_name']; ?>.
    <br><br><br><br><br>

    Create a new transaction
    <br><br>
    <form method="post">
        Name*  
        <input id="text" type="text" name="order_name" placeholder="Enter name"><br><br>
        
        Price   
        <input id="text" type="text" name="order_price" placeholder="Enter price ($CAD)"><br><br>
        
        Quantity   
        <input id="text" type="text" name="order_quantity" placeholder="Enter quantity"><br><br>

        Date   
        <input id="text" type="date" name="order_date" placeholder="Enter date"><br><br>
        
        <label style="font-weight: normal;" for="order_status">
        Status*
        </label>
        <select id="type" name="order_status">
            <option>Select status</option>
            <option value="In progress">In progress</option>
            <option value="Completed">Completed</option>
            <option value="Cancelled">Cancelled</option>
        </select><br><br>
        
        <label style="font-weight: normal;" for="order_type">
        Type
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
                    //inserts 
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
    <br><br><br><br><br>
    View and edit transactions
    <?php
        //connecting to db and selecting everything
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "inv_mgmt_db";
        $db = new mysqli($dbhost, $dbuser, $dbpass, $dbname); 

        //administrator account, who has an ID of 0, can see all users' orders and non-sensitive info
        if($user_data['user_id'] == 0){
            admin_list();
        }

        //user can only see their own orders, which are linked to their id
        else{
            $user_id = $user_data['user_id'];
            $query = "SELECT * FROM user_orders WHERE user_id IN (SELECT user_id FROM users WHERE user_id ='$user_id');";

            //creating table and table headers
            echo '<div class="table-responsive">  
                    <table id="orders">
                        <thead>
                            <tr>
                                <th>Order ID</td> 
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
                echo '<tr> 
                        <td>'.$row['order_id'].'</td> 
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
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/jquery-tabledit@1.0.0/jquery.tabledit.min.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>  
    $('#orders').Tabledit({
        url: 'db_updater.php',
        columns: {
            identifier:[0, 'order_id'],
            editable:[
            [1, 'order_name'], [2, 'order_price'], [3, 'order_quantity'], [4, 'order_date'], [5, 'order_type', '{"Select type": "Select type", "Buying": "Buying", "Selling": "Selling", "Renting": "Renting", "Loaning": "Loaning", "Other": "Other"}'], [6, 'order_status', '{"Select status": "Select status", "In progress": "In progress", "Completed": "Completed", "Cancelled": "Cancelled"}']]
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
 </script>
</body>
</html>
