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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];
    $new_email = $_POST["new_email"];

    
    $sql_details = "SELECT password, email FROM users WHERE id = $user_id";
    $result_details = $conn->query($sql_details);

    if ($result_details->num_rows > 0) {
        $row_details = $result_details->fetch_assoc();
        $stored_password = $row_details["password"];
        $stored_email = $row_details["email"];

       
        if (password_verify($current_password, $stored_password)) {
            
            if ($new_password === $confirm_password) {
            
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                
                $sql_update_password = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";

                if ($conn->query($sql_update_password) === TRUE) {
                    $successMessage = "Password updated successfully.";
                } else {
                    $errorMessage = "Error updating password: " . $conn->error;
                }
            } else {
                $errorMessage = "New password and confirm password do not match.";
            }

            if ($new_email !== $stored_email) {
               
                $sql_update_email = "UPDATE users SET email = '$new_email' WHERE id = $user_id";

                if ($conn->query($sql_update_email) === TRUE) {
                    $successMessageEmail = "Email updated successfully.";
                } else {
                    $errorMessageEmail = "Error updating email: " . $conn->error;
                }
            }
        } else {
            $errorMessage = "Incorrect current password.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
       
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color:lightyellow;
        }

        .container {
            max-width: 600px;
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

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .success-message,
        .error-message {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
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
            top:15px;
            left:15px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
        .bt{
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            text-decoration: solid;

        }
        .bt:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Update Details</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <label for="new_email">New Email:</label>
            <input type="email" id="new_email" name="new_email" value="<?php echo isset($new_email) ? $new_email : ''; ?>" required>
            <label for="current_password">Current Password:</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            
           <div class="bt">
            <button type="submit">Update Details</button></div>
        </form>

        <?php if (isset($successMessage)) : ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessage)) : ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (isset($successMessageEmail)) : ?>
            <div class="success-message"><?php echo $successMessageEmail; ?></div>
        <?php endif; ?>

        <?php if (isset($errorMessageEmail)) : ?>
            <div class="error-message"><?php echo $errorMessageEmail; ?></div>
        <?php endif; ?>
        <button class="back-btn" onclick="window.location.href='customer_dashboard.php'">Back</button>
    </div>
</body>

</html>
