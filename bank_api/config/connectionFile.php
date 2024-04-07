<?php
    
    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "userdetails";

    $conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
    
    if ($conn)
    {
        //secho"Connection SuccessFully userdetails \n";
    }else
    {
        die('Connect Error('.mysqli_connect_error().')'.mysqli_connect_error());
    }
    

?>