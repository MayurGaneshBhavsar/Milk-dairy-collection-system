<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
   
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_dairy";

$conn = mysqli_connect($servername, $username, $password, $dbname, 4306);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_customers = "SELECT u.full_name AS customer_name, IFNULL(p.status, 'Not Paid') AS payment_status 
                  FROM users u 
                  LEFT JOIN (
                      SELECT user_id, status 
                      FROM payment 
                      WHERE date = CURDATE()
                  ) p ON u.id = p.user_id
                  WHERE u.role != 'owner'";

$result_customers = mysqli_query($conn, $sql_customers);


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color:lightyellow;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 50%;
            border-collapse: collapse;
            margin: 0 auto;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .back-button {
            display: block;
            width: fit-content;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            position:absolute;
            top:15px;
            left: 15px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Payment Status</h1>

    <table>
        <tr>
            <th>Customer Name</th>
            <th>Payment Status</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result_customers)) : ?>
            <tr>
                <td><?php echo $row['customer_name']; ?></td>
                <td><?php echo $row['payment_status']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

   
    <a href="owner_dashboard.php" class="back-button">Back</a>
</body>

</html>
