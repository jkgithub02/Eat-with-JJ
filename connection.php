<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $db = "ewjj"; //change your database name here!!!

    //create the connection
    $conn = new mysqli($server, $username, $password, $db);

    //checks connection
    if(!$conn){
        die("Connection failed". mysqli_connect_error());
    }
?>