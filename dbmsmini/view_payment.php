<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    
    header("Location: index.php");
    exit();
}


include_once "connection.php"; 


$sql = "SELECT * FROM payment WHERE user_id = {$_SESSION['user_id']}";
$result = mysqli_query($conn, $sql);


$payment_data = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $payment_data[] = $row;
    }
}


mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Payment</title>
    <link rel="stylesheet" href="styles.css">
    <style>
      
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: lightyellow;
        }

       
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 80px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f2f2f2;
        }
       
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: lightyellow;
}

#headerSection {
    background-color: #007bff;
    color: white;
    padding: 10px;
    text-align: center;
    position: absolute;
    top: 150px;
    right:700px;
}

h1 {
    margin: 0;
}

.table-container {
    max-width: 700px;
    margin: 20px auto;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th,
td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f2f2f2;
}

.btn {
    text-align: center;
    margin-top: 20px;
}

.back-button {
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    position: absolute;
    top: 15px;
    left: 15px;
}

.back-button:hover {
    background-color: #0056b3;
}

    </style>
</head>

<body>
  
    <div id="headerSection">
        <h1>Payment</h1>
    </div>
<table>
        <tr>
            <th>Date</th>
            <th>Total Milk (liters)</th>
            <th>Fat (%)</th>
            <th>Amount (INR)</th>
            <th>Status</th>
        </tr>
        <?php foreach ($payment_data as $payment) : ?>
            <tr>
                <td><?php echo $payment['date']; ?></td>
                <td><?php echo $payment['total_milk']; ?></td>
                <td><?php echo $payment['fat']; ?></td>
                <td><?php echo $payment['amount']; ?></td>
                <td><?php echo $payment['status']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div class="btn">
        <a href="customer_dashboard.php" class="back-button">Back</a>
    </div>
</body>

</html>
