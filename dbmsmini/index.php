<!DOCTYPE html>
<html lang="en">

<head>
    <title>Milk Dairy | Welcome</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
       
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

      
        .header {
            display: flex;
            justify-content: flex-end; 
            padding: 10px;
            position:absolute;
            top:15px;
            right:15px;
        }

        
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }

       
        .container {
            text-align: center;
            margin-top: 50px;
        }
        .bd{
            font-size: 30px;
            font-family: 'Times New Roman', Times, serif;
        }
        .image{
            width: 30px;
            height:30vh;
            display:flex;
            justify-content: flex-end;
            position:absolute;
            top:15px;
            left:250px;

        }
        .image img{
            height:300px;
            width:300px;
            border-radius: 50%;
            border:10px solid #ffff;
        }
        .cow{
            width: 30px;
            height:30vh;
            display:flex;
            justify-content: flex-end;
            position:absolute;
            bottom:100px;
            right:-30px;

        }
        .cow img{
            height:300px;
            width:300px;
            border-radius: 50%;
            border:10px solid #ffff;
        }
     
    </style>
</head>

<body>
    
    <div class="header">
       
        <a href="login.php" class="btn">Login</a>

       
        <a href="registration.php" class="btn">Register</a>
    </div>
    <div class="image">
        <img src ="image/milk.jpg">
    </div>
    <div class="cow">
        <img src ="image/R.jpg">
    </div>

    
    <div class="bd">
        <h1>Welcome to Milk Dairy</h1>
    </div>

</body>

</html>
