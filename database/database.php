<?php 
    $db_name = "treeshop";
    $db_user = "root";
    $db_pass = "";
    $db_host = "localhost";
    $conn = "";

    try {    $conn = mysqli_connect($db_host,$db_user,
        $db_pass,$db_name);}
        catch (mysqli_sql_exception) {
            echo "Error connecting to database!";
        }

    if (!$conn){
        echo "Errorrrrrr";
    }
?>