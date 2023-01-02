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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/index_styles.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://kit.fontawesome.com/cc17318182.js" crossorigin="anonymous"></script>
    <title><?php echo $user_data['user_name']; ?>'s profits</title>
</head>
<body>
    <div class="nav">
        <div class="right">
            <a href="../include/logout.php">Logout</a>
        </div>
        <div class="left">
            <a href="">Transactions</a>
            <a href="profits.php">Profits</a>
        </div>
    </div>
</body>
</html>