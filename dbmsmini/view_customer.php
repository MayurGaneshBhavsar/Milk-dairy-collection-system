<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View and Edit Customer Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color:lightyellow;
        }

        .container {
            width: 60%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        button {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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

        .edit-form {
            margin-top: 20px;
        }

        .edit-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #45a049;
        }

        .back-button {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            position:absolute;
            top:15px;
            left:15px;
        }

        .back-button:hover {
            background-color: #555;
        }

        .success-message {
            color: green;
            margin-bottom: 20px;
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>View and Edit Customer Details</h1>
        <form action="view_customer.php" method="POST">
            <label for="search_name">Search by Name:</label>
            <input type="text" name="search_name" id="search_name" required>
            <button type="submit" name="search">Search</button>
        </form>

        <?php
       
        session_start();

       
        $full_name = "";
        $email = "";
        $user_id = null;
        $update_successful = false;
        $show_edit_form = false;

       
        if (isset($_POST['search'])) {
            $search_name = htmlspecialchars($_POST['search_name']);

        
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "milk_dairy";

            $conn = mysqli_connect($servername, $username, $password, $dbname, 4306);

            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            
            $sql_search = "SELECT id, full_name, email FROM users WHERE full_name LIKE '%$search_name%' AND role != 'owner'";
            $result_search = mysqli_query($conn, $sql_search);

            if (mysqli_num_rows($result_search) > 0) {
                $row_search = mysqli_fetch_assoc($result_search);
                $user_id = $row_search['id'];
                $full_name = $row_search['full_name'];
                $email = $row_search['email'];

        
                $sql_milk = "SELECT date, milk_type, `milk`, milk_fat, session FROM milk_details WHERE user_id = $user_id";
                $result_milk = mysqli_query($conn, $sql_milk);

                if ($full_name && $email) {
                    $show_edit_form = true;
                }
            }

     
            $conn->close();
        }

        if ($show_edit_form) : ?>
            <h2>Customer Details</h2>
            <p>Name: <?php echo $full_name; ?></p>
            <p>Email: <?php echo $email; ?></p>

            <h2>Milk Details</h2>
            <?php if (isset($result_milk) && mysqli_num_rows($result_milk) > 0) : ?>
                <table>
                    <tr>
                        <th>Date</th>
                        <th>Milk Type</th>
                        <th>Milk (in liter)</th>
                        <th>Milk Fat (%)</th>
                        <th>Session</th>
                    </tr>
                    <?php
                    while ($row_milk = mysqli_fetch_assoc($result_milk)) :
                        echo "<tr>";
                        echo "<td>" . $row_milk['date'] . "</td>";
                        echo "<td>" . $row_milk['milk_type'] . "</td>";
                        echo "<td>" . $row_milk['milk'] . "</td>";
                        echo "<td>" . $row_milk['milk_fat'] . "</td>";
                        echo "<td>" . $row_milk['session'] . "</td>";
                        echo "</tr>";
                    endwhile;
                    ?>
                </table>
            <?php else : ?>
                <p>No milk records found for this user.</p>
            <?php endif; ?>

            <?php if ($update_successful) : ?>
                <p style="color: green;">User details updated successfully!</p>
            <?php endif; ?>

            <div class="edit-form">
                <h2>Edit User Details</h2>
                <form action="view_customer.php" method="POST">
                    <label for="edited_name">Name:</label>
                    <input type="text" name="edited_name" id="edited_name" value="<?php echo $full_name; ?>" required>
                    <label for="edited_email">Email:</label>
                    <input type="email" name="edited_email" id="edited_email" value="<?php echo $email; ?>" required>
                    <button type="submit" name="edit" class="edit-button">Update Details</button>
                </form>
            </div>
        <?php elseif (isset($_POST['search'])) : ?>
            <p>No user details found for the entered name.</p>
        <?php endif; ?>

        <a href="javascript:history.back()" class="back-button">Back</a>
    </div>
</body>

</html>
