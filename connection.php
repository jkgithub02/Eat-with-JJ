<?php
$server = "localhost";
$username = "root";
$password = "";
$dbname = "ewjj";

//create the connection
$db = new mysqli($server, $username, $password, $dbname);

//checks connection
if(!$db){
    die("Connection failed". mysqli_connect_error());
}
?>