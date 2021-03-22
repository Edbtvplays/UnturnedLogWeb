<?php
session_start();
unset($_SESSION['userid']);
unset($_SESSION['name']);
unset($_SESSION['userid']);
header("Location:login.php");
?>