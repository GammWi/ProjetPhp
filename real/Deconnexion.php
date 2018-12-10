<?php
    session_start(); // if it's not already started.
    session_unset();
    session_destroy();
    header('Location:login.php'); 
?>
