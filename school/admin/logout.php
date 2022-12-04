<?php 
include_once "../includes/application.php";
include_once "../includes/header.php";

$Application = $GLOBALS['APP'];

$Application->Logout();
header("Location: ./index.php"); 
die(); 
?>