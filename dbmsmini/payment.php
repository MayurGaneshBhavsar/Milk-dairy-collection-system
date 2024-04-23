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


$name = "";
$total_morning_milk = 0;
$total_evening_milk = 0;
$avg_morning_fat = 0;
$avg_evening_fat = 0;
$payment_cow = 0;
$payment_buffalo = 0;
$successMessage = "";
$user_id = null; 


$payment_history = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["name"])) {
    $name = $_POST["name"];

    
    $sql_user = "SELECT id FROM users WHERE full_name = '$name'";
    $result_user = mysqli_query($conn, $sql_user);

    if (mysqli_num_rows($result_user) > 0) {
        $row_user = mysqli_fetch_assoc($result_user);
        $user_id = $row_user['id'];

        
        $sql_morning = "SELECT SUM(`milk`) AS total_milk, AVG(milk_fat) AS avg_fat FROM milk_details WHERE user_id = $user_id AND session = 'Morning'";
        $result_morning = mysqli_query($conn, $sql_morning);
        if ($result_morning && mysqli_num_rows($result_morning) > 0) {
            $row_morning = mysqli_fetch_assoc($result_morning);
            $total_morning_milk = $row_morning['total_milk'];
            $avg_morning_fat = $row_morning['avg_fat'];
        }

        
        $sql_evening = "SELECT SUM(`milk`) AS total_milk, AVG(milk_fat) AS avg_fat FROM milk_details WHERE user_id = $user_id AND session = 'Evening'";
        $result_evening = mysqli_query($conn, $sql_evening);
        if ($result_evening && mysqli_num_rows($result_evening) > 0) {
            $row_evening = mysqli_fetch_assoc($result_evening);
            $total_evening_milk = $row_evening['total_milk'];
            $avg_evening_fat = $row_evening['avg_fat'];
        }

       
        $rate_cow = 26;
        $rate_buffalo = 7; 

        $payment_cow = $total_morning_milk * $rate_cow * $avg_morning_fat +
            $total_evening_milk * $rate_cow * $avg_evening_fat;

        $payment_buffalo = $total_morning_milk * $rate_buffalo * $avg_morning_fat +
            $total_evening_milk * $rate_buffalo * $avg_evening_fat;

        // Check if payment is already done for the current session
        $sql_check_payment = "SELECT * FROM payment WHERE user_id = $user_id AND date = CURDATE()";
        $result_check_payment = mysqli_query($conn, $sql_check_payment);
        if ($result_check_payment && mysqli_num_rows($result_check_payment) > 0) {
            $successMessage = "Payment for today's session is done....";
        }
    } else {
        $successMessage = "No customer found with the name: $name";
    }
}

if (isset($_POST['paid']) && isset($user_id)) {
    $sql_insert = "INSERT INTO payment (user_id, date, total_milk, fat, amount, payment_date, status) 
                   VALUES ($user_id, CURDATE(), " . ($total_morning_milk + $total_evening_milk) . ",
                   ((" . $avg_morning_fat . " + " . $avg_evening_fat . ") / 2), " . ($payment_cow + $payment_buffalo) . ", NOW(), 'Paid')";

    if (mysqli_query($conn, $sql_insert)) {
        $successMessage = "Payment inserted for user ID: $user_id";

  
        header("Location: payment.php?name=" . urlencode($name));
        exit();
    } else {
        echo "Error: " . $sql_insert . "<br>" . mysqli_error($conn);
    }
}

if (isset($user_id)) {
    $sql_history = "SELECT * FROM payment WHERE user_id = $user_id";
    $result_history = mysqli_query($conn, $sql_history);

    if ($result_history && mysqli_num_rows($result_history) > 0) {
        while ($row_history = mysqli_fetch_assoc($result_history)) {
            $payment_history[] = $row_history;
        }
    }
}


$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generate Payment</title>
    <style>
       /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background-color:lightyellow;
}

.container {
            width: 80%;
            height: 80px    ;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1, h2, h3 {
    color: #333;
}

form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

}

label {
    display: block;
    margin-bottom: 8px;
    color: #333;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    margin-bottom: 10px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: none;
    background-color: #007bff;
    color: white;
    cursor: pointer;
    margin-top: 10px;
}

button:hover {
    background-color: #0056b3;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
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

.success-message {
    color: green;
    margin-top: 10px;
}

.back-button {
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    position:absolute;
    top:15px;
    left:15px;
}

.back-button:hover {
    background-color: #bbb;
}

    </style>
</head>

<body>
    <div style="max-width: 800px; margin: 0 auto;">
        <form action="payment.php" method="post">
            <label for="name">Customer Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <button type="submit">Generate Payment</button>
        </form>

        <?php if (!empty($successMessage)) : ?>
            <div class="success-message">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <?php if ($total_morning_milk > 0 || $total_evening_milk > 0) : ?>
            <h2>Payment Details for <?php echo $name; ?></h2>
            <table>
                <tr>
                    <th>Session</th>
                    <th>Total Milk (liters)</th>
                    <th>Average Fat (%)</th>
                </tr>
                <tr>
                    <td>Morning</td>
                    <td><?php echo $total_morning_milk; ?></td>
                    <td><?php echo $avg_morning_fat; ?></td>
                </tr>
                <tr>
                    <td>Evening</td>
                    <td><?php echo $total_evening_milk; ?></td>
                    <td><?php echo $avg_evening_fat; ?></td>
                </tr>
            </table>

            <h2>Payment Calculation</h2>
            <table>
                <tr>
                    <th>Session</th>
                    <th>Payment (INR)</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>Morning</td>
                    <td><?php echo $payment_cow; ?></td>
                    <td>
                        <?php if (empty($successMessage)) : ?>
                            <form action="payment.php" method="post">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" name="paid">Pay</button>
                            </form>
                        <?php else : ?>
                            Payment Done
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Evening</td>
                    <td><?php echo $payment_buffalo; ?></td>
                    <td>
                        <?php if (empty($successMessage)) : ?>
                            <form action="payment.php" method="post">
                                <input type="hidden" name="name" value="<?php echo $name; ?>">
                                <button type="submit" name="paid">Pay</button>
                            </form>
                        <?php else : ?>
                            Payment Done
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        <?php endif; ?>
    
    <?php if (!empty($payment_history)) : ?>
    <h2>Payment History</h2>
    <table>
        <tr>
            <th>Date</th>
            <th>Total Milk (liters)</th>
            <th>Fat (%)</th>
            <th>Amount (INR)</th>
            <th>Payment Date</th>
            <th>Status</th>
        </tr>
        <?php foreach ($payment_history as $history) : ?>
            <?php
            $total_milk = isset($history['total_milk']) ? $history['total_milk'] : '';
            $fat = isset($history['fat']) ? $history['fat'] : '';
            ?>
            <tr>
                <td><?php echo isset($history['date']) ? $history['date'] : ''; ?></td>
                <td><?php echo $total_milk; ?></td>
                <td><?php echo $fat; ?></td>
                <td><?php echo isset($history['amount']) ? $history['amount'] : ''; ?></td>
                <td><?php echo isset($history['payment_date']) ? $history['payment_date'] : ''; ?></td>
                <td><?php echo isset($history['status']) ? $history['status'] : ''; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
        <a href="javascript:history.back()" class="back-button">Back</a>
    </div>
</body>

</html>
