<?php
$servername = "localhost";
$username ="root";
$password ="";
$dbname = "milk_dairy";

$conn = mysqli_connect($servername, $username, $password, $dbname,4306);

//$conn = mysqli_connect($servername,$username,$password,$dbname);
//$mysqli = new mysqli("localhost","root","","milk_dairy");
/*if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connection successful";
}*/

?>