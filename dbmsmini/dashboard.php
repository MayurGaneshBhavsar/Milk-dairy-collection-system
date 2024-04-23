<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Milk Dairy | Dashboard</title>
    <link rel="stylesheet" href="styles.css">

<body>
    <div id="headerSection">
        <center><h1>Milk Dairy Dashboard</h1></center>
    </div>

    <div class="container">
        <center>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>
            <p>Your email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <div class="dashboard-links">
                <a href="profile.php">Profile</a>
                <a href="orders.php">Your Orders</a>
            </div>
            <form action="logout.php" method="post">
                <input type="submit" class="btn btn-primary" value="Logout" name="logout">
            </form>
        </center>
    </div>
</body>

</html>
