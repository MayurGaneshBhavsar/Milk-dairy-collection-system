<?php

session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    
    header("Location: index.php");
    exit();
}


$successMessage = "";


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_dairy";

$conn = mysqli_connect($servername, $username, $password, $dbname, 4306);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name = htmlspecialchars($_POST['name']);
    $milk_type = htmlspecialchars($_POST['milk_type']);
    $milk = floatval($_POST['milk']);
    $milk_fat = floatval($_POST['milk_fat']);
    $session = htmlspecialchars($_POST['session']);

 
    $date = date('Y-m-d');

    
    $stmt = $conn->prepare("SELECT id FROM users WHERE full_name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
       
        $stmt_check = $conn->prepare("SELECT id FROM milk_details WHERE user_id = ? AND date = ? AND session = ?");
        $stmt_check->bind_param("iss", $user_id, $date, $session);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "<div class='existing-entry-message'><p>An entry for the '$session' session already exists for $name on $date.</p>";
        } else {
          
            $stmt_insert = $conn->prepare("INSERT INTO milk_details (user_id, name, date, milk_type, `milk`, milk_fat, session) VALUES (?, ?, ?, ?, ?, ?, ?)");

            
            if ($stmt_insert) {
                
                $stmt_insert->bind_param("isssdds", $user_id, $name, $date, $milk_type, $milk, $milk_fat, $session);

             
                if ($stmt_insert->execute()) {
                    $successMessage = "Data inserted successfully!";
                    echo "<div class='success-container'><p class='success-message'>$successMessage</p></div>";
                } else {
                    echo "<p>Error inserting data: " . $stmt_insert->error . "</p>";
                }

              
                $stmt_insert->close();
            } else {
                echo "<p>Failed to prepare the statement: " . $conn->error . "</p>";
            }
        }
    } else {
        echo "<p>No user found with the provided name.</p>";
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Milk Measurement</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            width: 100%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        form label {
            display: block;
            font-weight: bold;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .btn {
            width: 50px;
            position: absolute;
            top: 15px;
            left: 15px;
        }

        .back-button {
            width: 50px;
            background-color: #007BFF;
            position: absolute;
            top: 15px;
            left: 15px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }


        .success-message {
            color: green;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }

        .existing-entry-message {

            color: #a94442;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .success-container {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            position: absolute;
            top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Milk Measurement</h1>
        <form action="measurement.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="milk_type">Milk Type:</label>
                <select name="milk_type" id="milk_type" required>
                    <option value="Cow">Cow</option>
                    <option value="Buffalo">Buffalo</option>
                </select>
            </div>

            <div class="form-group">
                <label for="milk">Milk (in liters):</label>
                <input type="number" step="0.01" name="milk" id="milk" required>
            </div>

            <div class="form-group">
                <label for="milk_fat">Milk Fat (%):</label>
                <input type="number" step="0.01" name="milk_fat" id="milk_fat" required>
            </div>

            <div class="form-group">
                <label for="session">Session:</label>
                <select name="session" id="session" required>
                    <option value="Morning">Morning</option>
                    <option value="Evening">Evening</option>
                </select>
            </div>

            <button type="submit">Submit</button>
        </form>
    </div>
    <div class="btn">
        <button onclick="window.location.href='owner_dashboard.php'">Back</button>
    </div>
</body>

</html>
