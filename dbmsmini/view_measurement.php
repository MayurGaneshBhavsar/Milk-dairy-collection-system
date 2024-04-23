<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    
    header("Location: index.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_dairy";


$conn = new mysqli($servername, $username, $password, $dbname,4306);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];


$sql_user = "SELECT * FROM users WHERE id = $user_id";
$result_user = $conn->query($sql_user);


$sql_milk = "SELECT * FROM milk_details WHERE user_id = $user_id";
$result_milk = $conn->query($sql_milk);


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Milk Measurement</title>
    <link rel="stylesheet" href="styles.css">
    <style>
       
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color:lightyellow;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            color: #333;
            margin-top: 0;
        }

        
        .user-details {
            margin-bottom: 20px;
        }

        .user-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .user-details th,
        .user-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .user-details th {
            background-color: #f2f2f2;
        }

        
        .milk-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .milk-details th,
        .milk-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .milk-details th {
            background-color: #f2f2f2;
        }

        .milk-details tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .milk-details tr:hover {
            background-color: #f2f2f2;
        }

        .back-btn {
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            position:absolute;
            top: 15px;
            left: 15px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>View Milk Measurement</h1>

        <div class="user-details">
            <h2>User Details</h2>
            <table>
                <?php if ($result_user && $result_user->num_rows > 0) {
                    $row_user = $result_user->fetch_assoc(); ?>
                    <tr>
                        <th>User ID</th>
                        <td><?php echo $row_user['id']; ?></td>
                    </tr>
                    <tr>
                        <th>Full Name</th>
                        <td><?php echo $row_user['full_name']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

       
        <div class="milk-details">
            <h2>Milk Measurement Details</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Session</th>
                    <th>Milk (liters)</th>
                    <th>Fat (%)</th>
                </tr>
                <?php if ($result_milk && $result_milk->num_rows > 0) {
                    while ($row_milk = $result_milk->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row_milk['date']; ?></td>
                            <td><?php echo $row_milk['session']; ?></td>
                            <td><?php echo $row_milk['milk']; ?></td>
                            <td><?php echo $row_milk['milk_fat']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="4">No milk measurement records found.</td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <button class="back-btn" onclick="window.location.href='customer_dashboard.php'">Back</button>
    </div>

</body>

</html>
