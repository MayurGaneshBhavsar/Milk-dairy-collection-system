<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Milk Rate Chart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color:lightyellow;       
        }

        h2 {
            text-align: center;
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
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
         
         .back-button {
            width: 5%; 
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            position:absolute;
            top:15px;
            left: 15px;
            text-decoration: none;
            text-align: center;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>

<body>
    <h2>Buffalo Milk Rate Chart </h2>
    <table>
        <tr>
            <th>Fat</th>
            <th>Price (INR)</th>
        </tr>
        <?php
        
        $buffalo_rates = array(
            array("5.0 - 7.0", "40 - 60"),
            array("8.0 - 9.0", "60 - 90")
        );

        foreach ($buffalo_rates as $rate) {
            echo "<tr>";
            echo "<td>" . $rate[0] . "</td>";
            echo "<td>" . $rate[1] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Cow Milk Rate Chart </h2>
    <table>
        <tr>
            <th>Fat </th>
            <th>Price (INR)</th>
        </tr>
        <?php
       
        $cow_rates = array(
            array("3.0 - 5.0", "30 - 50")
        );

        foreach ($cow_rates as $rate) {
            echo "<tr>";
            echo "<td>" . $rate[0] . "</td>";
            echo "<td>" . $rate[1] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div class="btn">
    <a href="javascript:history.back()" class="back-button">Back</a>
    </div>
</body>

</html>
