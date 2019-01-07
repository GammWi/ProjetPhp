<?php
    session_start(); // if it's not already started.
    session_unset();
    session_destroy();
	setcookie('rememberMe', '', time()-3600, '/');
	unset($_COOKIE['rememberMe']);
    header('Location:login.php'); 
?>
