<!DOCTYPE html>
<html>
    <head>
        <title>Milk Dairy | Registration</title>
        <title>Milk Dairy</title>
        <link rel="stylesheet" href="stylesheet.css">
    </head>
    <body>
        <div class="container">
        <div id="headersection">
        <h1>Milk Dairy</h1>
        <hr>
    </div>
        <h2>Registration</h2> 
        <?php
        if(isset($_POST["submit"])){
            $fullname = $_POST["fullname"];
            $email = $_POST["email"];
            $role = $_POST["role"];
            $password= $_POST["password"];
            $cpassword= $_POST["cpass"];
            $errors = array();
            if(empty($fullname)OR empty($mobile) OR empty($role) OR empty($password) OR empty($cpassword)){
                array_push($errors,"All fields are required");
            }
            if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                array_push($errors,"Email is not valid");
            }
            if(strlen($password)<8)
            {
                array_push($errors,"Password must be at least 8 characters long");
            }
            if($password!==$cpassword)
            {
                array_push($errors,"Password does not match");
            }

            if(count($errors)>0)
            {
                foreach($errors as $error){
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            }
            else{
                require_once "connection.php";
                $sql = "INSERT INTO users (full_name,email,role,password)VALUES(?,?,?,?)";
                mysqli_stmt_init($conn);
                $prepareStmt=mysqli_stmt_prepare($stmt,$sql);
                if($prepareStmt){
                    mysqli_stmt_bind_param($stmt,"ssss",$fullname,$email,$role,$password);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>You are registered successfuly</div>";
                }
                else{
                    die("Something went wrong");
                }
            }
        }
        ?>

        <form action="registration.php" method="post">
            <div class="form-group">
            <input type="text" class="form-control" name="fullname" placeholder="Full Name">
            <p><input type="email" class="form-control" name="email" placeholder="Email"></p>
            Select Role:<br>
            <select name="role" id="dropbox">
                <option value="">Select Option</option>
                <option value="Owner">Owner</option>
                <option value="Customer">Customer</option>
            </select>
            <p><input type="password" class="form-control" name="password" placeholder="Password"></p>
           <p> <input type="text" class="form-control" name="cpass" placeholder="Confirm password"></p>
            <p>
            <center>
    </fieldset>
</center></p>
         <div class="from-btn">
            <p><input type="submit" class="btn btn-primary" value="Register" name="Submit"></p>
         </div>
            <p>Alrady Register?<a href="login.php">Login</a></p>
        </form>
        </div>
    </body>
</html>