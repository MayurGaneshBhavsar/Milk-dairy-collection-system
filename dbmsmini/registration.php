<!DOCTYPE html>
<html lang="en">

<head>
    <title>Milk Dairy | Registration Form</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div id="headerSection">
        <center><h1>Milk Dairy</h1></center>
        <div class="container">
            <center><h1>Registration</h1></center>
            <?php
            if (isset($_POST["submit"])) {
                $fullName = $_POST["fullname"];
                $email = $_POST["email"];
                $password = $_POST["password"];
                $passwordRepeat = $_POST["repeat_password"];
                $role = $_POST["role"];

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $errors = array();

               
                if (empty($fullName) || empty($email) || empty($password) || empty($passwordRepeat) || empty($role)) {
                    array_push($errors, "All fields are required");
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    array_push($errors, "Email is not valid");
                }
                if (strlen($password) < 8) {
                    array_push($errors, "Password must be at least 8 characters long");
                }
                if ($password !== $passwordRepeat) {
                    array_push($errors, "Password does not match");
                }
                if ($role !== "owner" && $role !== "customer") {
                    array_push($errors, "Invalid role selected");
                }

                require_once "connection.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);
                if ($rowCount > 0) {
                    array_push($errors, "Email already exists!");
                }

                if (count($errors) > 0) {
                    foreach ($errors as $error) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                } else {
                   
                    $sql = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
                    if ($prepareStmt) {
                        mysqli_stmt_bind_param($stmt, "ssss", $fullName, $email, $passwordHash, $role);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are registered successfully.</div>";
                    } else {
                        die("Something went wrong");
                    }
                }
            }
            ?>

            <form action="registration.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" name="fullname" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password" required>
                </div>
                
                <div class="sele">
                <div class="form-group">
                    <select class="form-control" name="role" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="owner">Owner</option>
                        <option value="customer">Customer</option>
                    </select>
                </div>
                </div>
                <div class="form-btn">
                    <input type="submit" class="btn btn-primary" value="Register" name="submit">
                </div>
            </form>

            <div>
                <p>Already Registered? <a href="login.php">Login Here</a></p>
            </div>
        </div>
    </div>
</body>

</html>
