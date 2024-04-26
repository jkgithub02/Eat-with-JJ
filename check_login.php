<?php
//start session
session_start();
//check if user logged in
echo isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] ? 'true' : 'false'; 
?>