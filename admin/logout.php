<?php
session_start();
//destroy the session
session_destroy();
//redirect to admin login page
header('Location: index.php');
exit();
?>