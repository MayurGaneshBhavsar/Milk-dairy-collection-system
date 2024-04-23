<!DOCTYPE html>
<html lang="en">

<head>
    <title>Milk Dairy | Login Form</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div id="headerSection">
        <center><h1>Milk Dairy</h1></center>
        <div class="container">
            <center><h1>Login</h1></center>
            <?php
            session_start();
            require_once "connection.php";
            if (isset($_POST['submit'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $errors = array();
                if (empty($email) || empty($password)) {
                    array_push($errors, "Both email and password are required.");
                } else {
                    $sql = "SELECT * FROM users WHERE email = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (mysqli_stmt_prepare($stmt, $sql)) {
                        mysqli_stmt_bind_param($stmt, 's', $email);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($row = mysqli_fetch_assoc($result)) {
                           
                            if (password_verify($password, $row['password'])) {
                               
                                echo "<div class='alert alert-success'>Login successful. Welcome, {$row['full_name']}!</div>";
                                
                                $_SESSION['user_id'] = $row['id'];
                                $_SESSION['email'] = $row['email'];
                                $_SESSION['full_name'] = $row['full_name'];
                                $_SESSION['role'] = $row['role'];

                               
                                if ($_SESSION['role'] === 'owner') {
                                    header("Location: owner_dashboard.php");
                                } elseif ($_SESSION['role'] === 'customer') {
                                    header("Location: customer_dashboard.php");
                                }

                                exit(); 
                            } else {
                                array_push($errors, "Incorrect password.");
                            }
                        } else {
                            array_push($errors, "No user found with the provided email.");
                        }
                    } else {
                        die("Something went wrong with the database query.");
                    }
                }

                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                }
            }
            ?>

       
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Login" name="submit">
                </div>
            </form>
            <div>
                <p>Don't have an account? <a href="registration.php">Register Here</a></p>
            </div>
        </div>
    </div>
</body>

</html>
