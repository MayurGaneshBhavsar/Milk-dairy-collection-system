<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {

    header("Location: index.php");
    exit();

}


date_default_timezone_set('Asia/Kolkata');


$current_date_time = date('Y-m-d H:i:s'); 

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            background-size: cover; 
              
    background-position: center; 
    
}
        

        
        #headerSection {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            margin-left: 250px;
            
        }

        
        #sidebar {
            width: 250px;
            height: 100vh; 
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            position: fixed; 
            top: 0; 
            left: 0; 
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        #mainContent {
            margin-left: 260px; 
            padding: 20px;
        }

        
        button {
            width: 100%; 
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

       
        .logout-btn {
    width: 80px;
    padding: 10px;
    font-size: 14px;
    background-color: red;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    position: absolute;
    left: 15px;
    bottom: 15px;
    z-index: 1; 
}
        .logout-btn:hover {
            background-color: darkred;
        }

        #date-time {
            
            width: 10%; 
            height:40px;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
    margin: 0; 
    position:absolute;
    top: 15px;
    right:15px;
}
.image {
    background-image: url('image/back.jpg'); 
    width: 100%; 
    background-size: cover; 
    background-repeat: no-repeat; 
    background-position: right center; 
    background-attachment: fixed; 
}
    </style>
</head>

<body>
<div class="image">
        <img src ="image/bac.jpg"></div>

   
        <div id="date-time">
            
            <p><?php echo $current_date_time; ?></p>
            
</div>

            <div id=logout>
            <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
     
    </div>

    
    <div id="sidebar">
       <center> <h1>Milk Dairy</h1></center>
       
        <button onclick="window.location.href='view_measurement.php'">View Milk Measurement</button>
        <button onclick="window.location.href='update_details.php'">Update Details</button>
        <button onclick="window.location.href='chart.php'">See Rate Chart</button>
        <button onclick="window.location.href='view_payment.php'">View Payment</button>
    </div>

   
    <div id="mainContent">
        
        
    </div>

</body>

</html>