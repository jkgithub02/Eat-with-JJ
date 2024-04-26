<?php
session_start();
header('Content-Type: application/json');
//convert cart value into json string
echo json_encode($_SESSION['cart']); 
?>
